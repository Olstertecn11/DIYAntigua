<?php

namespace App\Listeners;

use App\Events\ReservaCreada;
use App\Mail\ReservaConfirmada;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnviarCorreoReserva implements ShouldQueue
{
    public int $tries = 3;

    public int $timeout = 20;

    public function backoff(): array
    {
        return [30, 120, 300];
    }

    public function handle(ReservaCreada $event)
    {
        try {
            Mail::to($event->reserva->correo_cliente)
                ->send(new ReservaConfirmada($event->reserva));
        } catch (\Throwable $exception) {
            Log::warning('No se pudo enviar el correo de reserva.', [
                'reservacion_id' => $event->reserva->id,
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }
}
