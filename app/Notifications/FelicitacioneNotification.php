<?php

namespace App\Notifications;

use App\Modelos\Empleado;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FelicitacioneNotification extends Notification
{
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    use Queueable;


    public function __construct($post)
    {
       $this->post = $post;
    }


    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    public function toArray($notifiable)
    {
        $v = User::select('name')->where('id',$this->post->user_id)->get()->toArray();
        return [
            // 'tipo'=>'cumpleaños',
            'mensaje' => 'Feliz vuelta al sol te desea',
            'creacion'=>Carbon::now()->diffForHumans(),
            'Usuario' => $v,
            'avatar' => 'cake'
        ];
    }
}
