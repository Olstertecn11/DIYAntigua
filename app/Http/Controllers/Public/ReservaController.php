<?php
namespace App\Http\Controllers\Public;

use App\Events\ReservaCreada;
use App\Http\Controllers\Controller;
use App\Models\Reservacion;
use App\Models\Ruta;
use Illuminate\Http\Request;
use App\Models\Vehiculo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReservaController extends Controller
{
    public function cotizar(Request $request)
    {
        $ruta = Ruta::with(['origen', 'destino', 'vehiculosDisponibles'])
            ->where('origen_id', $request->origen)
            ->where('destino_id', $request->destino)
            ->where('activa', true)
            ->firstOrFail();

        $datos = [
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'pasajeros' => $request->pasajeros,
        ];

        return view('reservas.cotizar', compact('ruta', 'datos'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'ruta_id' => 'required|exists:rutas,id',
            'vehiculo_id' => 'required|exists:vehiculos,id',
            'nombre_cliente' => 'required|string|max:150',
            'correo_cliente' => 'required|email|max:150',
            'telefono_cliente' => 'required|string|max:25',
            'punto_recogida' => 'required|string',
            'punto_destino' => 'required|string',
            'fecha_viaje' => 'required|date|after_or_equal:today',
            'hora_viaje' => 'required',
            'pasajeros' => 'required|integer|min:1',
            'precio_total' => 'required|numeric',
        ]);

        try {
            return DB::transaction(function () use ($request, $validated) {

                $vehiculo = DB::table('vehiculos')->where('id', $request->vehiculo_id)->first();

                $notasCompletas = "RECOGIDA: " . $request->punto_recogida . "\n" .
                    "DESTINO: " . $request->punto_destino . "\n" .
                    "NOTAS: " . ($request->notas_adicionales ?? 'Ninguna');

                $reserva = Reservacion::create([
                    'codigo_reserva'   => 'DIY-' . strtoupper(Str::random(8)),
                    'ruta_id'          => $request->ruta_id,
                    'user_id'          => auth()->id(),
                    'fecha_viaje'      => $request->fecha_viaje,
                    'hora_viaje'       => $request->hora_viaje,
                    'pasajeros'        => $request->pasajeros,
                    'tipo_vehiculo'    => strtolower($vehiculo->nombre),
                    'nombre_cliente'   => $request->nombre_cliente,
                    'correo_cliente'   => $request->correo_cliente,
                    'telefono_cliente' => $request->telefono_cliente,
                    'notas_adicionales'=> $notasCompletas,
                    'precio_total'     => $request->precio_total,
                    'estado_pago'      => 'pendiente',
                    'estado_viaje'     => 'programado',
                ]);

                event(new ReservaCreada($reserva));

                return redirect()->route('reservas.confirmar', ['codigo' => $reserva->codigo_reserva])
                     ->with('success', '¡Reserva creada con éxito!');
            });

        } catch (\Exception $e) {
            return back()->withErrors('Error al procesar la reserva: ' . $e->getMessage())->withInput();
        }
    }


    public function confirmar($codigo)
    {
        $reservacion = Reservacion::where('codigo_reserva', $codigo)->firstOrFail();
        return view('reservas.confirmar', compact('reservacion'));
    }


    public function detalles(Request $request) {
        $request->validate([
            'ruta_id' => 'required',
            'vehiculo_id' => 'required', // Antes era tipo_vehiculo
            'id_detalle_ruta' => 'required',
            'fecha' => 'required',
            'precio' => 'required'
        ]);

        $ruta = Ruta::with(['origen', 'destino', 'vehiculosDisponibles'])->findOrFail($request->ruta_id);

        $datos = $request->all();

        return view('reservas.detalles', compact('ruta', 'datos'));
    }
}
