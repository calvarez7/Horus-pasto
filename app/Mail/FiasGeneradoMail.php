<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FiasGeneradoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $archivo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $archivo)
    {   
        $this->data = $data;
        $this->archivo = $archivo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $nombre_archivo = $this->data['tipo_fias'] . $this->data['mes'] . $this->data['anio'] . '.txt';
        return $this->view('emails.fias_generado')
            ->attachFromStorage($this->archivo,$nombre_archivo, [
                'mime' => 'text/plain',
            ]);
    }
}
