<?php

namespace App\Mail;

use App\Models\PaymentTransaction;
use App\Models\Reservacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PagoReservaAprobado extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Reservacion $reserva,
        public PaymentTransaction $transaction
    ) {
    }

    public function build()
    {
        $this->reserva->loadMissing(['ruta.origen', 'ruta.destino']);

        return $this->subject('Pago aprobado - ' . $this->reserva->codigo_reserva)
            ->view('emails.pago_reserva_aprobado');
    }
}
