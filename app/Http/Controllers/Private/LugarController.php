<?php

namespace App\Http\Controllers\Private;

use App\Http\Controllers\Controller;
use App\Models\Lugar;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LugarController extends Controller
{
    public function index()
    {
        $lugares = Lugar::orderBy('nombre')->get();
        return Inertia::render('Admin/Lugares/Index', [
            'lugares' => $lugares->map(fn (Lugar $lugar) => [
                'id' => $lugar->id,
                'nombre' => $lugar->nombre,
                'ciudad' => $lugar->ciudad,
                'urls' => [
                    'destroy' => route('admin.lugares.destroy', $lugar),
                ],
            ])->values(),
            'urls' => [
                'store' => route('admin.lugares.store'),
            ],
        ]);
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
