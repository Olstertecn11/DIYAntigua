<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Reservacion;
use App\Services\Reservations\ReservationCancellationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserReservationController extends Controller
{
    public function index(Request $request)
    {
        $reservas = Reservacion::with(['ruta.origen', 'ruta.destino', 'paymentTransactions' => fn ($q) => $q->latest()])
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return Inertia::render('Reservas/User/Index', [
            'reservas' => $reservas->through(fn (Reservacion $reserva) => $this->formatReservation($reserva)),
            'urls' => [
                'home' => route('welcome'),
            ],
        ]);
    }

    public function show(Request $request, Reservacion $reserva)
    {
        abort_unless((int) $reserva->user_id === (int) $request->user()->id, 403);

        $reserva->load([
            'ruta.origen',
            'ruta.destino',
            'paymentTransactions' => fn ($q) => $q->latest(),
        ]);

        return Inertia::render('Reservas/User/Show', [
            'reserva' => $this->formatReservation($reserva, true),
            'urls' => [
                'index' => route('reservas.mine.index'),
                'pdf' => route('reservas.pdf', $reserva->codigo_reserva),
                'cancel' => route('reservas.mine.cancel', $reserva),
            ],
        ]);
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

    private function formatReservation(Reservacion $reserva, bool $detail = false): array
    {
        $travelAt = $reserva->travelDateTime();
        $transaction = $reserva->paymentTransactions->first();

        return [
            'id' => $reserva->id,
            'codigo_reserva' => $reserva->codigo_reserva,
            'nombre_cliente' => $reserva->nombre_cliente,
            'correo_cliente' => $reserva->correo_cliente,
            'telefono_cliente' => $reserva->telefono_cliente,
            'notas_adicionales' => $reserva->notas_adicionales,
            'tipo_vehiculo' => $reserva->tipo_vehiculo,
            'pasajeros' => (int) $reserva->pasajeros,
            'precio_total' => (float) $reserva->precio_total,
            'estado_pago' => $reserva->estado_pago,
            'estado_viaje' => $reserva->estado_viaje,
            'reembolso_estado' => $reserva->reembolso_estado,
            'can_cancel' => $reserva->canBeCancelledWithRefund(),
            'travel_at' => $travelAt->format('d/m/Y H:i'),
            'travel_iso' => $travelAt->toIso8601String(),
            'travel_date' => $travelAt->format('d/m/Y'),
            'travel_time' => $travelAt->format('H:i'),
            'ruta' => [
                'label' => trim(($reserva->ruta?->origen?->nombre ?? 'Origen') . ' -> ' . ($reserva->ruta?->destino?->nombre ?? 'Destino')),
            ],
            'transaction' => $detail && $transaction ? [
                'reference' => $transaction->reference,
                'card_brand' => $transaction->card_brand,
                'card_last_four' => $transaction->card_last_four,
            ] : null,
            'urls' => [
                'show' => route('reservas.mine.show', $reserva),
            ],
        ];
    }
}
