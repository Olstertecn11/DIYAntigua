<?php
namespace App\Mail;

use App\Models\Reservacion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservaConfirmada extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $reserva;

    public function __construct(Reservacion $reserva)
    {
        $this->reserva = $reserva;
    }

    public function build()
    {
        return $this->subject('Confirmación de tu Reserva - DiyAntigua')
                    ->view('emails.reserva_confirmada');
    }
}
