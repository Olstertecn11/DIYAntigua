<?php

namespace App\Http\Controllers\Private;

use App\Http\Controllers\Controller;
use App\Models\Conductor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ConductorController extends Controller
{
    public function index()
    {
        $conductores = Conductor::orderBy('nombre', 'asc')->get();
        return Inertia::render('Admin/Conductores/Index', [
            'conductores' => $conductores->map(fn (Conductor $conductor) => [
                'id' => $conductor->id,
                'nombre' => $conductor->nombre,
                'telefono' => $conductor->telefono,
                'vehiculo_modelo' => $conductor->vehiculo_modelo,
                'placa' => $conductor->placa,
                'estado' => $conductor->estado,
                'urls' => [
                    'update' => route('admin.conductores.update', $conductor),
                    'destroy' => route('admin.conductores.destroy', $conductor),
                ],
            ])->values(),
            'urls' => [
                'store' => route('admin.conductores.store'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'vehiculo_modelo' => ['nullable', 'string', 'max:191'],
            'placa' => ['required', 'string', 'max:30', 'unique:conductores,placa'],
            'estado' => ['required', Rule::in(['activo', 'inactivo', 'en_viaje'])],
        ]);

        Conductor::create($validated);

        return redirect()->back()->with('success', 'Conductor registrado con éxito.');
    }

    public function update(Request $request, Conductor $conductor)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'vehiculo_modelo' => ['nullable', 'string', 'max:191'],
            'placa' => ['required', 'string', 'max:30', Rule::unique('conductores', 'placa')->ignore($conductor->id)],
            'estado' => ['required', Rule::in(['activo', 'inactivo', 'en_viaje'])],
        ]);

        $conductor->update($validated);

        return redirect()->back()->with('success', 'Conductor actualizado.');
    }

    public function destroy(Conductor $conductore) // Laravel pluraliza a 'conductore' por convención si no se define
    {
        $conductore->delete();
        return redirect()->back()->with('success', 'Conductor eliminado.');
    }
}
