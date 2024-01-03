<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AsignacionActividadMail extends Mailable
{
    use Queueable, SerializesModels;

    public $actividad;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($actividad)
    {
        $this->actividad = $actividad;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nueva actividad')
        ->view('desarrollo.asignacion_actividad');
    }
}
