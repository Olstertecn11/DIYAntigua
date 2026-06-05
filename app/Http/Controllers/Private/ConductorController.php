<?php

namespace App\Http\Controllers\Private;

use App\Http\Controllers\Controller;
use App\Models\Conductor;
use Illuminate\Http\Request;
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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string',
            'placa' => 'required|string|unique:conductores',
        ]);

        Conductor::create($request->all());

        return redirect()->back()->with('success', 'Conductor registrado con éxito.');
    }

    public function destroy(Conductor $conductore) // Laravel pluraliza a 'conductore' por convención si no se define
    {
        $conductore->delete();
        return redirect()->back()->with('success', 'Conductor eliminado.');
    }
}
