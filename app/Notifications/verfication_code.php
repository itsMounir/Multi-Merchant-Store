<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class verfication_code extends Notification
{
    use Queueable;


    public function __construct(public $code)
    {

    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->mailer('smtp')
        ->greeting('Hello :'.$notifiable->first_name)
        ->line("Your verfication code : $this->code");
    }


    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
