<?php

namespace App\Http\Controllers\Private;

use App\Http\Controllers\Controller;
use App\Models\Ruta;
use App\Models\Lugar;
use Illuminate\Http\Request;

class RutaController extends Controller
{
    public function index()
    {
        // Cargamos rutas con sus lugares y los vehículos asociados con su precio (pivot)
        $rutas = Ruta::with(['origen', 'destino', 'vehiculosDisponibles'])->get();
        $lugares = Lugar::orderBy('nombre')->get();
        $vehiculos = \App\Models\Vehiculo::where('activo', 1)->get();

        return view('admin.rutas.index', compact('rutas', 'lugares', 'vehiculos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'origen_id' => 'required',
            'destino_id' => 'required',
            'vehiculos' => 'required|array|min:1',
        ]);

        // 1. Crear la ruta base
        $ruta = Ruta::create([
            'origen_id' => $request->origen_id,
            'destino_id' => $request->destino_id,
            'kilometraje' => $request->kilometraje,
        ]);

        // 2. Adjuntar vehículos con sus precios
        // El formato esperado es: [id_vehiculo => ['precio_tarifa' => valor]]
        foreach ($request->vehiculos as $v) {
            $ruta->vehiculosDisponibles()->attach($v['id'], [
                'precio_tarifa' => $v['precio']
            ]);
        }

        return back()->with('success', 'Ruta y tarifas configuradas correctamente.');
    }

    public function destroy(Ruta $ruta)
    {
        $ruta->delete();
        return back()->with('success', 'Ruta eliminada.');
    }
}
