<?php

namespace App\Services\Reservations;

use App\Models\Reservacion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReservationCancellationService
{
    public function cancel(Reservacion $reservacion, User $user, ?string $reason = null): Reservacion
    {
        return DB::transaction(function () use ($reservacion, $user, $reason) {
            $locked = Reservacion::whereKey($reservacion->id)->lockForUpdate()->firstOrFail();

            if ($locked->estado_viaje === 'cancelado') {
                throw ValidationException::withMessages([
                    'reserva' => 'Esta reserva ya fue cancelada.',
                ]);
            }

            if (! $locked->canBeCancelledWithRefund()) {
                throw ValidationException::withMessages([
                    'reserva' => 'Esta reserva ya está dentro de las 24 horas previas al viaje y no puede cancelarse con reembolso.',
                ]);
            }

            $refundState = match ($locked->estado_pago) {
                'pagado' => 'pendiente',
                'procesando' => 'revision',
                default => 'no_aplica',
            };

            $locked->update([
                'estado_viaje' => 'cancelado',
                'estado_pago' => $locked->estado_pago === 'pagado' ? 'reembolso_pendiente' : $locked->estado_pago,
                'cancelado_at' => now(),
                'cancelado_por' => $user->id,
                'motivo_cancelacion' => $reason,
                'reembolso_estado' => $refundState,
                'reembolso_monto' => $refundState === 'pendiente' ? $locked->precio_total : null,
                'reembolso_solicitado_at' => in_array($refundState, ['pendiente', 'revision'], true) ? now() : null,
            ]);

            return $locked->refresh();
        });
    }
}
