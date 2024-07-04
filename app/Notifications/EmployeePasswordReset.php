<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeePasswordReset extends Notification
{
    use Queueable;

    protected $user;
    protected $new_password;

    /**
     * Create a new notification instance.
     */
    public function __construct($user,$new_password)
    {
        $this->user = $user;
        $this->new_password = $new_password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
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
            ->subject('تم تغيير كلمة المرور')
            ->greeting($this->user->name . 'مرحباً')
            ->line('تم تعيين كلمة مرور جديدة لحسابك')
            ->line($this->new_password . ': كلمة المرور ');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
