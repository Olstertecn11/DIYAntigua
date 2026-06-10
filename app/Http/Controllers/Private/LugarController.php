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
                'estado' => $lugar->estado,
                'urls' => [
                    'update' => route('admin.lugares.update', $lugar),
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
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:191'],
            'ciudad' => ['nullable', 'string', 'max:191'],
            'estado' => ['nullable', 'string', 'max:191'],
        ]);

        Lugar::create($validated);
        return back()->with('success', 'Lugar guardado con éxito.');
    }

    public function update(Request $request, Lugar $lugare)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:191'],
            'ciudad' => ['nullable', 'string', 'max:191'],
            'estado' => ['nullable', 'string', 'max:191'],
        ]);

        $lugare->update($validated);

        return back()->with('success', 'Lugar actualizado.');
    }

    public function destroy(Lugar $lugare)
    {
        $lugare->delete();
        return back()->with('success', 'Lugar eliminado.');
    }
}
