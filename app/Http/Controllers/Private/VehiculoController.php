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
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:80'],
            'min_pasajeros' => ['required', 'integer', 'min:1', 'max:255'],
            'max_pasajeros' => ['required', 'integer', 'gte:min_pasajeros', 'max:255'],
            'icono' => ['nullable', 'string', 'max:80'],
            'activo' => ['boolean'],
        ]);

        Vehiculo::create([
            'nombre' => $validated['nombre'],
            'min_pasajeros' => $validated['min_pasajeros'],
            'max_pasajeros' => $validated['max_pasajeros'],
            'icono' => $validated['icono'] ?? 'fa-car',
            'activo' => $request->boolean('activo', true),
        ]);

        return back()->with('success', 'Vehículo registrado correctamente.');
    }

    public function update(Request $request, Vehiculo $vehiculo)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:80'],
            'min_pasajeros' => ['required', 'integer', 'min:1', 'max:255'],
            'max_pasajeros' => ['required', 'integer', 'gte:min_pasajeros', 'max:255'],
            'icono' => ['nullable', 'string', 'max:80'],
            'activo' => ['boolean'],
        ]);

        $vehiculo->update([
            'nombre' => $validated['nombre'],
            'min_pasajeros' => $validated['min_pasajeros'],
            'max_pasajeros' => $validated['max_pasajeros'],
            'icono' => $validated['icono'] ?? $vehiculo->icono,
            'activo' => $request->boolean('activo'),
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
