<?php

namespace App\Http\Controllers\Private;

use App\Http\Controllers\Controller;
use App\Models\Lugar;
use Illuminate\Http\Request;

class LugarController extends Controller
{
    public function index()
    {
        $lugares = Lugar::orderBy('nombre')->get();
        return view('admin.lugares.index', compact('lugares'));
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:191']);
        Lugar::create($request->all());
        return back()->with('success', 'Lugar guardado con éxito.');
    }

    public function destroy(Lugar $lugare)
    {
        $lugare->delete();
        return back()->with('success', 'Lugar eliminado.');
    }
}
