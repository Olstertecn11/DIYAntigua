<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Reservacion;
use App\Services\Reservations\ReservationCancellationService;
use Illuminate\Http\Request;

class UserReservationController extends Controller
{
    public function index(Request $request)
    {
        $reservas = Reservacion::with(['ruta.origen', 'ruta.destino', 'paymentTransactions' => fn ($q) => $q->latest()])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('reservas.user.index', compact('reservas'));
    }

    public function show(Request $request, Reservacion $reserva)
    {
        abort_unless((int) $reserva->user_id === (int) $request->user()->id, 403);

        $reserva->load([
            'ruta.origen',
            'ruta.destino',
            'paymentTransactions' => fn ($q) => $q->latest(),
        ]);

        return view('reservas.user.show', compact('reserva'));
    }

    public function cancel(Request $request, Reservacion $reserva, ReservationCancellationService $service)
    {
        abort_unless((int) $reserva->user_id === (int) $request->user()->id, 403);

        $validated = $request->validate([
            'motivo_cancelacion' => ['nullable', 'string', 'max:500'],
        ]);

        $service->cancel($reserva, $request->user(), $validated['motivo_cancelacion'] ?? null);

        return redirect()
            ->route('reservas.mine.show', $reserva)
            ->with('success', 'Tu reserva fue cancelada. Si aplica, el reembolso quedó en revisión administrativa.');
    }
}
