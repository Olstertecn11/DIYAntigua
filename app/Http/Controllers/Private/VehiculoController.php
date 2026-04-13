<?php
namespace App\Http\Controllers\Private;

use App\Http\Controllers\Controller;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    public function index()
    {
        $vehiculos = Vehiculo::orderBy('max_pasajeros', 'asc')->get();
        return view('admin.vehiculos.index', compact('vehiculos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'min_pasajeros' => 'required|integer|min:1',
            'max_pasajeros' => 'required|integer|gte:min_pasajeros',
        ]);

        Vehiculo::create([
            'nombre' => $request->nombre,
            'min_pasajeros' => $request->min_pasajeros,
            'max_pasajeros' => $request->max_pasajeros,
            'icono' => $request->icono ?? 'fa-car',
            'activo' => $request->has('activo')
        ]);

        return back()->with('success', 'Vehículo registrado correctamente.');
    }

    public function update(Request $request, Vehiculo $vehiculo)
    {
        $vehiculo->update([
            'nombre' => $request->nombre,
            'min_pasajeros' => $request->min_pasajeros,
            'max_pasajeros' => $request->max_pasajeros,
            'activo' => $request->has('activo')
        ]);

        return back()->with('success', 'Vehículo actualizado.');
    }

    public function destroy(Vehiculo $vehiculo)
    {
        // Nota: Podrías validar si tiene rutas asignadas antes de borrar
        $vehiculo->delete();
        return back()->with('success', 'Vehículo eliminado.');
    }
}
