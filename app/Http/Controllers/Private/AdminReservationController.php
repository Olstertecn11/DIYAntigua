<?php

namespace App\Http\Controllers\Private;

use App\Http\Controllers\Controller;
use App\Models\Reservacion;
use App\Services\Reservations\ReservationCancellationService;
use Illuminate\Http\Request;

class AdminReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservacion::with(['ruta.origen', 'ruta.destino', 'user', 'socio.afiliadoInfo', 'paymentTransactions' => fn ($q) => $q->latest()]);

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('codigo_reserva', 'like', "%{$search}%")
                    ->orWhere('nombre_cliente', 'like', "%{$search}%")
                    ->orWhere('correo_cliente', 'like', "%{$search}%")
                    ->orWhere('telefono_cliente', 'like', "%{$search}%");
            });
        }

        if ($request->filled('estado_pago')) {
            $query->where('estado_pago', $request->estado_pago);
        }

        if ($request->filled('estado_viaje')) {
            $query->where('estado_viaje', $request->estado_viaje);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha_viaje', $request->fecha);
        }

        $statsBase = Reservacion::query();

        $stats = [
            'total' => (clone $statsBase)->count(),
            'pagadas' => (clone $statsBase)->where('estado_pago', 'pagado')->count(),
            'programadas' => (clone $statsBase)->where('estado_viaje', 'programado')->count(),
            'reembolsos' => (clone $statsBase)->where('reembolso_estado', 'pendiente')->count(),
        ];

        $reservas = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reservas.index', compact('reservas', 'stats'));
    }

    public function show(Reservacion $reserva)
    {
        $reserva->load([
            'ruta.origen',
            'ruta.destino',
            'user',
            'socio.afiliadoInfo',
            'canceladoPor',
            'paymentAttempts' => fn ($q) => $q->latest(),
            'paymentTransactions' => fn ($q) => $q->latest(),
        ]);

        return view('admin.reservas.show', compact('reserva'));
    }

    public function cancel(Request $request, Reservacion $reserva, ReservationCancellationService $service)
    {
        $validated = $request->validate([
            'motivo_cancelacion' => ['nullable', 'string', 'max:500'],
        ]);

        $service->cancel($reserva, $request->user(), $validated['motivo_cancelacion'] ?? null);

        return redirect()
            ->route('admin.reservas.show', $reserva)
            ->with('success', 'Reserva cancelada. Si aplica, el reembolso quedó pendiente de gestión.');
    }
}
