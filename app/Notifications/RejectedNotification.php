<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;
class RejectedNotification extends Notification implements ShouldBroadcast, ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $supplier)
    {

    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('supplier-channel'),
        ];
    }

    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'فاتورة مرفوضة',
            'message' => "تم رفض فاتورتك من قبل المورد: {$this->supplier->store_name}",
        ]);
    }
}
