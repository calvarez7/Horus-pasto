<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetRequest extends Notification
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    use Queueable;

    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url('/recuperar/password/' . $this->token);

        return (new MailMessage)
            ->subject('Recuperar contraseña')
            ->line('Ha enviado una recuperación de contraseña desde la aplicación,
            si usted no ha realizado este procedimiento ignore este correo.')
            ->action('Click para cambiar contraseña', url($url))
            ->line('Gracias por hacer parte de esta transformación!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
