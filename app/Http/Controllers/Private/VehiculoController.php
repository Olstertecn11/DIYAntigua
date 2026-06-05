<?php
namespace App\Http\Controllers\Private;

use App\Http\Controllers\Controller;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VehiculoController extends Controller
{
    public function index()
    {
        $vehiculos = Vehiculo::orderBy('max_pasajeros', 'asc')->get();
        return Inertia::render('Admin/Vehiculos/Index', [
            'vehiculos' => $vehiculos->map(fn (Vehiculo $vehiculo) => [
                'id' => $vehiculo->id,
                'nombre' => $vehiculo->nombre,
                'min_pasajeros' => (int) $vehiculo->min_pasajeros,
                'max_pasajeros' => (int) $vehiculo->max_pasajeros,
                'icono' => $vehiculo->icono,
                'activo' => (bool) $vehiculo->activo,
                'urls' => [
                    'update' => route('admin.vehiculos.update', $vehiculo),
                    'destroy' => route('admin.vehiculos.destroy', $vehiculo),
                ],
            ])->values(),
            'urls' => [
                'store' => route('admin.vehiculos.store'),
            ],
        ]);
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
