<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class verfication_code_market extends Notification
{
    use Queueable;


    public function __construct(public $market,public $verificationCode)
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
        ->subject('رمز التحقق')
        ->greeting('مرحباً : وائل')
        ->line('رمز تحقق لاعادة تعيين كلمة مرور لحساب ماركت')
        ->line('اسم الماركت: ' . $this->market->first_name . ' ' . $this->market->last_name)
        ->line('رقم هاتف الماركت: ' . $this->market->phone_number)
        ->line('رمز التحقق: ' . $this->verificationCode);

    }


    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
