<?php

namespace App\Listeners;

use App\Events\ReservaCreada;
use App\Mail\ReservaConfirmada;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnviarCorreoReserva implements ShouldQueue
{
    public function handle(ReservaCreada $event)
    {
        // Accedemos a la reserva a través del evento
        Mail::to($event->reserva->correo_cliente)
            ->send(new ReservaConfirmada($event->reserva));
    }
}
