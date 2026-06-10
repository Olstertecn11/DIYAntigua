<?php

namespace App\Listeners;

use App\Events\ReservaCreada;
use App\Mail\ReservaConfirmada;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EnviarCorreoReserva
{
    public function handle(ReservaCreada $event)
    {
        try {
            Mail::to($event->reserva->correo_cliente)
                ->send(new ReservaConfirmada($event->reserva));
        } catch (\Throwable $exception) {
            Log::warning('No se pudo enviar el correo de reserva.', [
                'reservacion_id' => $event->reserva->id,
                'mailer' => config('mail.default'),
                'from' => config('mail.from.address'),
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }
}
