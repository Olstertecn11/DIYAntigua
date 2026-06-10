<?php

namespace App\Http\Controllers\Private;

use App\Http\Controllers\Controller;
use App\Models\Ruta;
use App\Models\Lugar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class RutaController extends Controller
{
    public function index()
    {
        // Cargamos rutas con sus lugares y los vehículos asociados con su precio (pivot)
        $rutas = Ruta::with(['origen', 'destino', 'vehiculosDisponibles'])->get();
        $lugares = Lugar::orderBy('nombre')->get();
        $vehiculos = \App\Models\Vehiculo::where('activo', 1)->get();

        return Inertia::render('Admin/Rutas/Index', [
            'rutas' => $rutas->map(fn (Ruta $ruta) => [
                'id' => $ruta->id,
                'origen' => $ruta->origen,
                'destino' => $ruta->destino,
                'origen_id' => $ruta->origen_id,
                'destino_id' => $ruta->destino_id,
                'kilometraje' => $ruta->kilometraje,
                'activa' => (bool) $ruta->activa,
                'vehiculos' => $ruta->vehiculosDisponibles->map(fn ($vehiculo) => [
                    'id' => $vehiculo->id,
                    'nombre' => $vehiculo->nombre,
                    'precio_tarifa' => (float) $vehiculo->pivot->precio_tarifa,
                ])->values(),
                'urls' => [
                    'update' => route('admin.rutas.update', $ruta),
                    'destroy' => route('admin.rutas.destroy', $ruta),
                ],
            ])->values(),
            'lugares' => $lugares,
            'vehiculos' => $vehiculos,
            'urls' => [
                'store' => route('admin.rutas.store'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'origen_id' => [
                'required',
                'exists:lugares,id',
                Rule::unique('rutas')->where(fn ($query) => $query
                    ->where('origen_id', $request->origen_id)
                    ->where('destino_id', $request->destino_id)),
            ],
            'destino_id' => ['required', 'exists:lugares,id', 'different:origen_id'],
            'kilometraje' => ['nullable', 'numeric', 'min:0'],
            'activa' => ['boolean'],
            'vehiculos' => ['required', 'array', 'min:1'],
            'vehiculos.*.id' => ['required', 'exists:vehiculos,id', 'distinct'],
            'vehiculos.*.precio' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $ruta = Ruta::create([
                'origen_id' => $validated['origen_id'],
                'destino_id' => $validated['destino_id'],
                'kilometraje' => $validated['kilometraje'] ?? null,
                'activa' => $request->boolean('activa', true),
            ]);

            $ruta->vehiculosDisponibles()->sync($this->vehiclePrices($validated['vehiculos']));
        });

        return back()->with('success', 'Ruta y tarifas configuradas correctamente.');
    }

    public function update(Request $request, Ruta $ruta)
    {
        $validated = $request->validate([
            'origen_id' => [
                'required',
                'exists:lugares,id',
                Rule::unique('rutas')->where(fn ($query) => $query
                    ->where('origen_id', $request->origen_id)
                    ->where('destino_id', $request->destino_id))
                    ->ignore($ruta->id),
            ],
            'destino_id' => ['required', 'exists:lugares,id', 'different:origen_id'],
            'kilometraje' => ['nullable', 'numeric', 'min:0'],
            'activa' => ['boolean'],
            'vehiculos' => ['required', 'array', 'min:1'],
            'vehiculos.*.id' => ['required', 'exists:vehiculos,id', 'distinct'],
            'vehiculos.*.precio' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($ruta, $validated, $request) {
            $ruta->update([
                'origen_id' => $validated['origen_id'],
                'destino_id' => $validated['destino_id'],
                'kilometraje' => $validated['kilometraje'] ?? null,
                'activa' => $request->boolean('activa'),
            ]);

            $ruta->vehiculosDisponibles()->sync($this->vehiclePrices($validated['vehiculos']));
        });

        return back()->with('success', 'Ruta actualizada.');
    }

    public function destroy(Ruta $ruta)
    {
        $ruta->delete();
        return back()->with('success', 'Ruta eliminada.');
    }

    private function vehiclePrices(array $vehicles): array
    {
        return collect($vehicles)
            ->mapWithKeys(fn (array $vehicle) => [
                $vehicle['id'] => ['precio_tarifa' => $vehicle['precio']],
            ])
            ->all();
    }
}
