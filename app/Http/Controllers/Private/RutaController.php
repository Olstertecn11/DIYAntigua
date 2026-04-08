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
        // Traemos las rutas con sus nombres de origen y destino cargados
        $rutas = Ruta::with(['origen', 'destino'])->get();
        $lugares = Lugar::orderBy('nombre')->get();
        return view('admin.rutas.index', compact('rutas', 'lugares'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        // Convertir checkboxes a booleano manual si no vienen en el request
        $data['permite_sedan'] = $request->has('permite_sedan');
        $data['permite_suv'] = $request->has('permite_suv');
        $data['permite_bus'] = $request->has('permite_bus');

        Ruta::create($data);
        return back()->with('success', 'Tarifa de ruta creada.');
    }

    public function destroy(Ruta $ruta)
    {
        $ruta->delete();
        return back()->with('success', 'Ruta eliminada.');
    }
}
