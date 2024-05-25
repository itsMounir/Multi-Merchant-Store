<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class verfication_code_supplier extends Notification
{
    use Queueable;


    public function __construct(public $supplier,public $verificationCode)
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
        ->line('رمز تحقق لاعادة تعيين كلمة مرور لحساب مورد')
        ->line('اسم المورد: ' . $this->supplier->first_name . ' ' . $this->supplier->last_name)
        ->line('رقم هاتف المورد: ' . $this->supplier->phone_number)
        ->line('رمز التحقق: ' . $this->verificationCode);

    }


    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
