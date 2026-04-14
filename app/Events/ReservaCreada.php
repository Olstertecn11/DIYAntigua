<?php

namespace App\Events;

use App\Models\Reservacion;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReservaCreada
{
    use Dispatchable, SerializesModels;

    public $reserva;

    public function __construct(Reservacion $reserva)
    {
        $this->reserva = $reserva;
    }
}
