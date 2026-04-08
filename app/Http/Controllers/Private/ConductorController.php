<?php

namespace App\Http\Controllers\Private;

use App\Http\Controllers\Controller;
use App\Models\Conductor;
use Illuminate\Http\Request;

class ConductorController extends Controller
{
    public function index()
    {
        $conductores = Conductor::orderBy('nombre', 'asc')->get();
        return view('admin.conductores.index', compact('conductores'));
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
