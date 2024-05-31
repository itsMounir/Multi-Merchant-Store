<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class PendingBillNotification extends Notification
{
    use Queueable;

    public $newInvoicesCount;

    /**
     * Create a new notification instance.
     *
     * @param  int  $newInvoicesCount
     * @return void
     */
    public function __construct($newInvoicesCount)
    {
        $this->newInvoicesCount = $newInvoicesCount;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
                return ['database'];
    }

    public function databaseType(object $notifiable): string
    {
        return 'preparing-bill';
    }

    /**
     * Get the array representation of the notification for the database channel.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => "لديك {$this->newInvoicesCount} فواتير لم يتم تسليمها تحتاج إلى مراجعتها.",
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return IlluminateNotificationsMessagesMailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('إشعار بفواتير جديدة')
                    ->greeting('مرحباً!')
                    ->line("لديك {$this->newInvoicesCount} فاتورة/فواتير جديدة تحتاج إلى مراجعتها.")
                    ->action('عرض الفواتير', url('/invoices'))
                    ->line('شكراً لاستخدامك منصتنا!');
    }
}
