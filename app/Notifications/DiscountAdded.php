<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;
class DiscountAdded extends Notification implements ShouldBroadcast, ShouldQueue
{
    use Queueable;

    private $supplier;
    private $discounts;

    public function __construct($supplier)
    {
        $this->supplier = $supplier;
     //   $this->discounts = $discounts;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('supplier-channel'),
        ];
    }

    public function via($notifiable)
    {
        return ['broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => 'خصم جديد',
            'body' => "{$this->supplier->first_name} {$this->supplier->last_name} قام بإضافة خصم جديد."
        ]);
    }
}
