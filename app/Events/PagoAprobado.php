<?php

namespace App\Events;

use App\Models\PaymentTransaction;
use App\Models\Reservacion;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PagoAprobado
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Reservacion $reserva,
        public PaymentTransaction $transaction
    ) {
    }
}
