<?php

namespace App\Http\Controllers\Public;

use App\Events\ReservaCreada;
use App\Http\Controllers\Controller;
use App\Models\EmailVerificationCode;
use App\Models\Reservacion;
use App\Models\Ruta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservaController extends Controller
{
    public function cotizar(Request $request)
    {
        $validated = $request->validate([
            'origen' => ['required', 'exists:lugares,id'],
            'destino' => ['required', 'exists:lugares,id', 'different:origen'],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required'],
            'pasajeros' => ['required', 'integer', 'min:1', 'max:15'],
        ]);

        $ruta = Ruta::with(['origen', 'destino', 'vehiculosDisponibles'])
            ->where('origen_id', $validated['origen'])
            ->where('destino_id', $validated['destino'])
            ->where('activa', true)
            ->first();

        if (! $ruta) {
            return back()
                ->withErrors(['ruta' => 'No existe una ruta activa para el origen y destino seleccionados.'])
                ->withInput();
        }

        $datos = [
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'pasajeros' => $validated['pasajeros'],
        ];

        $images = [
            'sedan' => asset('images/sedan_image.jpg'),
            'suv' => asset('images/suv_image.jpg'),
            'van' => asset('images/micro_image.jpg'),
        ];

        return view('reservas.cotizar', compact('ruta', 'datos', 'images'));
    }

    public function detalles(Request $request)
    {
        $validated = $request->validate([
            'ruta_id' => ['required', 'exists:rutas,id'],
            'vehiculo_id' => ['required', 'exists:vehiculos,id'],
            'id_detalle_ruta' => ['required'],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required'],
            'pasajeros' => ['required', 'integer', 'min:1', 'max:15'],
            'precio' => ['required', 'numeric', 'min:0'],
        ]);

        $ruta = Ruta::with(['origen', 'destino', 'vehiculosDisponibles'])
            ->where('activa', true)
            ->findOrFail($validated['ruta_id']);

        $vehiculoSeleccionado = $ruta->vehiculosDisponibles
                                     ->where('id', (int) $validated['vehiculo_id'])
                                     ->first();

        if (! $vehiculoSeleccionado) {
            return redirect()
                ->route('reservas.cotizar', [
                    'origen' => $ruta->origen_id,
                    'destino' => $ruta->destino_id,
                    'fecha' => $validated['fecha'],
                    'hora' => $validated['hora'],
                    'pasajeros' => $validated['pasajeros'],
                ])
                ->withErrors(['vehiculo_id' => 'El vehículo seleccionado no está disponible para esta ruta.']);
        }

        $datos = $validated;

        return view('reservas.detalles', compact('ruta', 'datos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ruta_id' => ['required', 'exists:rutas,id'],
            'vehiculo_id' => ['required', 'exists:vehiculos,id'],
            'id_detalle_ruta' => ['required'],
            'nombre_cliente' => ['required', 'string', 'max:150'],
            'correo_cliente' => ['required', 'email', 'max:150'],
            'telefono_cliente' => ['required', 'string', 'max:25'],
            'punto_recogida' => ['required', 'string'],
            'punto_destino' => ['required', 'string'],
            'notas_adicionales' => ['nullable', 'string'],
            'fecha_viaje' => ['required', 'date', 'after_or_equal:today'],
            'hora_viaje' => ['required'],
            'pasajeros' => ['required', 'integer', 'min:1', 'max:15'],
            'precio_total' => ['required', 'numeric', 'min:0'],
            'email_verification_token' => ['required', 'string', 'size:64'],
        ]);

        $email = strtolower(trim($validated['correo_cliente']));
        $tokenHash = hash('sha256', $validated['email_verification_token']);

        $verification = EmailVerificationCode::where('email', $email)
            ->where('purpose', 'reservation')
            ->where('verification_token_hash', $tokenHash)
            ->whereNotNull('verified_at')
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $verification) {
            return back()
                ->withErrors(['correo_cliente' => 'La verificación de correo es inválida o venció. Solicita un nuevo código.'])
                ->withInput();
        }

        try {
            return DB::transaction(function () use ($validated, $verification, $email) {
                $ruta = Ruta::with(['vehiculosDisponibles'])
                    ->where('activa', true)
                    ->findOrFail($validated['ruta_id']);

                $vehiculo = $ruta->vehiculosDisponibles
                                 ->where('id', (int) $validated['vehiculo_id'])
                                 ->first();

                if (! $vehiculo) {
                    return back()
                        ->withErrors(['vehiculo_id' => 'El vehículo seleccionado no está disponible para esta ruta.'])
                        ->withInput();
                }

                $notasCompletas = "RECOGIDA: {$validated['punto_recogida']}\n"
                    . "DESTINO: {$validated['punto_destino']}\n"
                    . "NOTAS: " . ($validated['notas_adicionales'] ?? 'Ninguna');

                $reserva = Reservacion::create([
                    'codigo_reserva' => $this->generarCodigoReserva(),
                    'ruta_id' => $validated['ruta_id'],
                    'user_id' => auth()->id(),
                    'fecha_viaje' => $validated['fecha_viaje'],
                    'hora_viaje' => $validated['hora_viaje'],
                    'pasajeros' => $validated['pasajeros'],
                    'tipo_vehiculo' => strtolower($vehiculo->nombre),
                    'nombre_cliente' => $validated['nombre_cliente'],
                    'correo_cliente' => $email,
                    'telefono_cliente' => $validated['telefono_cliente'],
                    'notas_adicionales' => $notasCompletas,
                    'precio_total' => $validated['precio_total'],
                    'estado_pago' => 'pendiente',
                    'estado_viaje' => 'programado',
                ]);

                $verification->update([
                    'used_at' => now(),
                ]);

                event(new ReservaCreada($reserva));

                return redirect()
                    ->route('payments.checkout', ['codigo' => $reserva->codigo_reserva])
                    ->with('success', 'Reserva creada. Completa el pago para confirmarla.');
            });
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['reserva' => 'Error al procesar la reserva: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function confirmar($codigo)
    {
        $reservacion = Reservacion::where('codigo_reserva', $codigo)->firstOrFail();

        return view('reservas.confirmar', compact('reservacion'));
    }

    public function descargarPDF($codigo)
    {
        $reserva = Reservacion::with(['ruta.origen', 'ruta.destino'])
            ->where('codigo_reserva', $codigo)
            ->firstOrFail();

        $path = public_path('images/logo.png');
        $logoBase64 = '';

        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $pdf = Pdf::loadView('reservas.comprobante_pdf', compact('reserva', 'logoBase64'));
        $pdf->setPaper('letter', 'portrait');

        return $pdf->download("Comprobante-{$reserva->codigo_reserva}.pdf");
    }

    private function generarCodigoReserva()
    {
        do {
            $codigo = 'DIY-' . strtoupper(Str::random(8));
        } while (Reservacion::where('codigo_reserva', $codigo)->exists());

        return $codigo;
    }
}
