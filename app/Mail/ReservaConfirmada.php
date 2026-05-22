<?php
namespace App\Mail;

use App\Models\Reservacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservaConfirmada extends Mailable
{
    use Queueable, SerializesModels;

    public $reserva;

    public function __construct(Reservacion $reserva)
    {
        $this->reserva = $reserva;
    }

    public function build()
    {
        $this->reserva->loadMissing(['ruta.origen', 'ruta.destino']);

        return $this->subject('Reserva recibida - ' . $this->reserva->codigo_reserva)
                    ->view('emails.reserva_confirmada');
    }
}
