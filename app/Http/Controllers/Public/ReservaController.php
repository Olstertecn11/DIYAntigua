<?php

namespace App\Http\Controllers\Public;

use App\Events\ReservaCreada;
use App\Http\Controllers\Controller;
use App\Models\EmailVerificationCode;
use App\Models\Reservacion;
use App\Models\Ruta;
use App\Models\RutaVehiculo;
use App\Services\Affiliates\ReferralTracker;
use App\Services\Payments\PaymentManager;
use App\Support\PhoneNumber;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
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
            'id_detalle_ruta' => ['required', 'integer', 'exists:ruta_vehiculo,id'],
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
        $fingerprintSessionId = 'DIYQ' . Str::upper(Str::random(24));
        $fingerprintFullSessionId = config('qpaypro.fingerprint_prefix') . $fingerprintSessionId;
        $fingerprintOrgId = config('qpaypro.fingerprint_org_id');

        return view('reservas.detalles', compact('ruta', 'datos', 'fingerprintSessionId', 'fingerprintFullSessionId', 'fingerprintOrgId'));
    }

    public function store(Request $request, PaymentManager $paymentManager, ReferralTracker $referrals)
    {
        $validated = $request->validate([
            'ruta_id' => ['required', 'exists:rutas,id'],
            'vehiculo_id' => ['required', 'exists:vehiculos,id'],
            'id_detalle_ruta' => ['required', 'integer', 'exists:ruta_vehiculo,id'],
            'nombre_cliente' => ['required', 'string', 'max:150'],
            'correo_cliente' => ['required', 'email', 'max:150'],
            'telefono_country_code' => ['required', 'string', 'in:' . implode(',', array_keys(config('phone.countries', [])))],
            'telefono_national' => ['required', 'string', 'max:30', 'regex:/^[0-9\s().-]{5,30}$/'],
            'punto_recogida' => ['required', 'string'],
            'punto_destino' => ['required', 'string'],
            'notas_adicionales' => ['nullable', 'string'],
            'fecha_viaje' => ['required', 'date', 'after_or_equal:today'],
            'hora_viaje' => ['required'],
            'pasajeros' => ['required', 'integer', 'min:1', 'max:15'],
            'precio_total' => ['required', 'numeric', 'min:0'],
            'email_verification_token' => ['nullable', 'string', 'max:64'],
            'cc_name' => ['required', 'string', 'max:120'],
            'cc_number' => ['required', 'string', 'regex:/^[0-9\s-]{13,23}$/'],
            'cc_exp_month' => ['required', 'regex:/^(0[1-9]|1[0-2])$/'],
            'cc_exp_year' => ['required', 'digits:4', 'integer', 'min:' . now()->year, 'max:' . now()->addYears(20)->year],
            'cc_cvv2' => ['required', 'string', 'regex:/^[0-9]{3,4}$/'],
            'cc_type' => ['required', 'in:visa,mastercard'],
            'billing_address' => ['required', 'string', 'max:191'],
            'billing_city' => ['required', 'string', 'max:100'],
            'billing_state' => ['required', 'string', 'max:100'],
            'billing_country' => ['required', 'string', 'max:100'],
            'billing_zip' => ['required', 'string', 'max:20'],
            'finger' => ['nullable', 'string', 'max:191'],
            'fingerprint_session_id' => ['required', 'string', 'max:191'],
        ]);

        $email = strtolower(trim($validated['correo_cliente']));
        $authenticatedEmail = auth()->check() && strtolower((string) auth()->user()->email) === $email;
        $verification = null;

        if (! $authenticatedEmail) {
            if (empty($validated['email_verification_token']) || strlen($validated['email_verification_token']) !== 64) {
                return back()
                    ->withErrors(['correo_cliente' => 'Verifica tu correo antes de continuar con la reserva.'])
                    ->withInput();
            }

            $verification = EmailVerificationCode::where('email', $email)
                ->where('purpose', 'reservation')
                ->where('verification_token_hash', hash('sha256', $validated['email_verification_token']))
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
        }

        $lock = Cache::lock('reservation-submit:' . sha1((string) $request->session()->getId()), 120);

        if (! $lock->get()) {
            return back()
                ->withErrors(['reserva' => 'Ya estamos procesando tu reserva y pago. Espera un momento.'])
                ->withInput();
        }

        try {
            $reserva = DB::transaction(function () use ($validated, $verification, $email, $request, $referrals) {
                $ruta = Ruta::with(['vehiculosDisponibles'])
                    ->where('activa', true)
                    ->findOrFail($validated['ruta_id']);

                $detalleRuta = RutaVehiculo::whereKey($validated['id_detalle_ruta'])
                    ->where('ruta_id', $ruta->id)
                    ->where('vehiculo_id', $validated['vehiculo_id'])
                    ->first();

                $vehiculo = $detalleRuta
                    ? $ruta->vehiculosDisponibles->where('id', (int) $validated['vehiculo_id'])->first()
                    : null;

                if (! $vehiculo) {
                    return back()
                        ->withErrors(['vehiculo_id' => 'El vehículo seleccionado no está disponible para esta ruta.'])
                        ->withInput();
                }

                $notasCompletas = "RECOGIDA: {$validated['punto_recogida']}\n"
                    . "DESTINO: {$validated['punto_destino']}\n"
                    . "NOTAS: " . ($validated['notas_adicionales'] ?? 'Ninguna');

                $socioId = $referrals->currentSocioId($request);
                $precioTotal = (float) $detalleRuta->precio_tarifa;

                $reserva = Reservacion::create([
                    'codigo_reserva' => $this->generarCodigoReserva(),
                    'ruta_id' => $validated['ruta_id'],
                    'user_id' => auth()->id(),
                    'socio_id' => $socioId,
                    'fecha_viaje' => $validated['fecha_viaje'],
                    'hora_viaje' => $validated['hora_viaje'],
                    'pasajeros' => $validated['pasajeros'],
                    'tipo_vehiculo' => strtolower($vehiculo->nombre),
                    'nombre_cliente' => $validated['nombre_cliente'],
                    'correo_cliente' => $email,
                    'telefono_cliente' => PhoneNumber::format($validated['telefono_country_code'], $validated['telefono_national']),
                    'notas_adicionales' => $notasCompletas,
                    'precio_total' => $precioTotal,
                    'comision_socio' => $referrals->commissionFor($socioId, $precioTotal),
                    'estado_pago' => 'pendiente',
                    'estado_viaje' => 'programado',
                ]);

                if ($verification) {
                    $verification->update([
                        'used_at' => now(),
                    ]);
                }

                DB::afterCommit(function () use ($reserva) {
                    event(new ReservaCreada($reserva));
                });

                return $reserva;
            });

            $transaction = $paymentManager->pay($reserva, [
                'cc_name' => $validated['cc_name'],
                'cc_number' => preg_replace('/\D+/', '', $validated['cc_number']),
                'cc_exp_month' => $validated['cc_exp_month'],
                'cc_exp_year' => $validated['cc_exp_year'],
                'cc_cvv2' => $validated['cc_cvv2'],
                'cc_type' => strtolower($validated['cc_type']),
                'billing_address' => $validated['billing_address'],
                'billing_city' => $validated['billing_city'],
                'billing_state' => $validated['billing_state'],
                'billing_country' => $validated['billing_country'],
                'billing_zip' => $validated['billing_zip'],
                'finger' => $validated['finger'] ?: '',
                'fingerprint_session_id' => $validated['fingerprint_session_id'],
            ], $request);

            if ($transaction->status === 'approved') {
                $referrals->clear($request);
            }

            return redirect(URL::signedRoute('payments.result', ['transaction' => $transaction]));
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['reserva' => 'Error al procesar la reserva: ' . $e->getMessage()])
                ->withInput();
        } finally {
            optional($lock)->release();
        }
    }

    public function confirmar($codigo)
    {
        $reservacion = Reservacion::where('codigo_reserva', $codigo)->firstOrFail();

        return view('reservas.confirmar', compact('reservacion'));
    }

    public function descargarPDF($codigo)
    {
        $reserva = Reservacion::with([
            'ruta.origen',
            'ruta.destino',
            'paymentTransactions' => fn ($query) => $query->latest(),
        ])
            ->where('codigo_reserva', $codigo)
            ->firstOrFail();

        $logoBase64 = $this->localImageDataUri(public_path('images/logo.png'));
        $qrPayload = route('reservas.confirmar', $reserva->codigo_reserva);
        $qrBase64 = $this->remoteImageDataUri('https://api.qrserver.com/v1/create-qr-code/?size=220x220&margin=12&data=' . urlencode($qrPayload));
        $transaction = $reserva->paymentTransactions->first();

        $pdf = Pdf::loadView('reservas.comprobante_pdf', compact('reserva', 'logoBase64', 'qrBase64', 'transaction'));
        $pdf->setPaper('letter', 'portrait');

        return $pdf->download("Comprobante-{$reserva->codigo_reserva}.pdf");
    }

    private function localImageDataUri(string $path): string
    {
        if (! file_exists($path)) {
            return '';
        }

        $type = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = match ($type) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            default => 'image/' . $type,
        };

        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
    }

    private function remoteImageDataUri(string $url): string
    {
        try {
            $response = Http::timeout(8)->get($url);

            if (! $response->successful()) {
                return '';
            }

            $contentType = $response->header('Content-Type') ?: 'image/png';

            return 'data:' . $contentType . ';base64,' . base64_encode($response->body());
        } catch (\Throwable) {
            return '';
        }
    }

    private function generarCodigoReserva()
    {
        do {
            $codigo = 'DIY-' . strtoupper(Str::random(8));
        } while (Reservacion::where('codigo_reserva', $codigo)->exists());

        return $codigo;
    }
}
