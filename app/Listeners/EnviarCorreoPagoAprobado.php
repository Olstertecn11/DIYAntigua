<?php

namespace App\Listeners;

use App\Events\PagoAprobado;
use App\Mail\PagoReservaAprobado;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnviarCorreoPagoAprobado implements ShouldQueue
{
    public int $tries = 3;

    public int $timeout = 20;

    public function backoff(): array
    {
        return [30, 120, 300];
    }

    public function handle(PagoAprobado $event): void
    {
        try {
            Mail::to($event->reserva->correo_cliente)
                ->send(new PagoReservaAprobado($event->reserva, $event->transaction));
        } catch (\Throwable $exception) {
            Log::warning('No se pudo enviar el correo de pago aprobado.', [
                'reservacion_id' => $event->reserva->id,
                'payment_transaction_id' => $event->transaction->id,
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }
}
