<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Ruta;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function cotizar(Request $request)
    {
        $ruta = Ruta::with(['origen', 'destino'])
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

    public function store(Request $request) {
        $reserva = new Reservacion();
        $reserva->codigo_reserva = 'RES-' . strtoupper(Str::random(6)); // Ej: RES-K82JS1
        // ... asignar campos ...
        $reserva->save();

        return view('reservas.confirmar', compact('reserva'));
    }


    public function detalles(Request $request) {
        // Validamos que vengan los datos necesarios
        $request->validate([
            'ruta_id' => 'required',
            'tipo_vehiculo' => 'required',
            'fecha' => 'required',
        ]);

        // Aquí buscas la ruta de nuevo para mostrar los nombres en el resumen
        $ruta = Ruta::with(['origen', 'destino'])->findOrFail($request->ruta_id);
        $datos = $request->all();

        return view('reservas.detalles', compact('ruta', 'datos'));
    }
}
