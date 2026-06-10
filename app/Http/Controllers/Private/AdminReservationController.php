<?php

namespace App\Http\Controllers\Private;

use App\Http\Controllers\Controller;
use App\Models\Reservacion;
use App\Services\Reservations\ReservationCancellationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

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

        return Inertia::render('Admin/Reservas/Index', [
            'reservas' => $reservas->through(fn (Reservacion $reserva) => $this->formatReservation($reserva)),
            'stats' => $stats,
            'filters' => [
                'search' => $request->search,
                'estado_pago' => $request->estado_pago,
                'estado_viaje' => $request->estado_viaje,
                'fecha' => $request->fecha,
            ],
            'urls' => [
                'index' => route('admin.reservas.index'),
            ],
        ]);
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

        return Inertia::render('Admin/Reservas/Show', [
            'reserva' => $this->formatReservation($reserva, true),
            'urls' => [
                'index' => route('admin.reservas.index'),
                'pdf' => route('reservas.pdf', $reserva->codigo_reserva),
                'cancel' => route('admin.reservas.cancel', $reserva),
                'status' => route('admin.reservas.status', $reserva),
                'refund' => route('admin.reservas.refund', $reserva),
            ],
        ]);
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

    public function updateStatus(Request $request, Reservacion $reserva)
    {
        $validated = $request->validate([
            'estado_viaje' => ['required', Rule::in(['programado', 'en_progreso', 'completado', 'cancelado'])],
        ]);

        $reserva->update($validated);

        return back()->with('success', 'Estado de viaje actualizado.');
    }

    public function updateRefund(Request $request, Reservacion $reserva)
    {
        $validated = $request->validate([
            'reembolso_estado' => ['nullable', Rule::in(['no_aplica', 'pendiente', 'revision', 'aprobado', 'rechazado', 'procesado'])],
            'reembolso_monto' => ['nullable', 'numeric', 'min:0', 'max:' . max((float) $reserva->precio_total, 0)],
        ]);

        $reserva->update([
            'reembolso_estado' => $validated['reembolso_estado'] ?: null,
            'reembolso_monto' => $validated['reembolso_monto'] ?? null,
            'reembolso_solicitado_at' => in_array($validated['reembolso_estado'] ?? null, ['pendiente', 'revision'], true)
                ? ($reserva->reembolso_solicitado_at ?? now())
                : $reserva->reembolso_solicitado_at,
        ]);

        return back()->with('success', 'Reembolso actualizado.');
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
            'comision_socio' => (float) $reserva->comision_socio,
            'estado_pago' => $reserva->estado_pago,
            'estado_viaje' => $reserva->estado_viaje,
            'reembolso_estado' => $reserva->reembolso_estado,
            'reembolso_monto' => (float) $reserva->reembolso_monto,
            'cancelado_at' => $reserva->cancelado_at?->format('d/m/Y H:i'),
            'can_cancel' => $reserva->canBeCancelledWithRefund(),
            'refund_message' => $reserva->canBeCancelledWithRefund()
                ? 'Cumple politica de mas de 24 horas para reembolso.'
                : 'No cumple politica de mas de 24 horas.',
            'policy_label' => $reserva->canBeCancelledWithRefund() ? 'Cancelable con reembolso' : 'Fuera de politica',
            'hours_until_travel' => $reserva->hoursUntilTravel(),
            'travel_at' => $travelAt->format('d/m/Y H:i'),
            'travel_date' => $travelAt->format('d/m/Y H:i'),
            'is_guest' => ! $reserva->user_id,
            'ruta' => [
                'origen' => $reserva->ruta?->origen?->nombre,
                'destino' => $reserva->ruta?->destino?->nombre,
            ],
            'user' => $reserva->user ? [
                'name' => $reserva->user->name,
                'email' => $reserva->user->email,
            ] : [
                'name' => 'Invitado',
                'email' => null,
            ],
            'socio' => $reserva->socio ? [
                'name' => $reserva->socio->afiliadoInfo?->nombre_comercial ?? $reserva->socio->name,
                'email' => $reserva->socio->email,
            ] : null,
            'transaction' => $detail && $transaction ? [
                'status' => $transaction->status,
                'reference' => $transaction->reference,
                'card_brand' => $transaction->card_brand,
                'card_last_four' => $transaction->card_last_four,
                'response_message' => $transaction->response_message,
            ] : null,
            'urls' => [
                'show' => route('admin.reservas.show', $reserva),
                'cancel' => route('admin.reservas.cancel', $reserva),
            ],
        ];
    }
}
