<?php

namespace App\Notifications;


use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;

class DistributionLocationUpdate extends Notification implements ShouldBroadcast
{
    public function __construct(
        public $supplier,
        public $newLocations
    ) {}

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'supplier_name' =>" {$this->supplier->first_name} {$this->supplier->middle_name} {$this->supplier->last_name}" ,
            'new_locations' => $this->newLocations
        ];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('supervisor-channel');
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title'=>'تعديل مناطق توزيع ',
            'body' => "{$this->supplier->first_name} {$this->supplier->last_name}  يرغب في تعديل مناطق التوزيع الخاصة به.",

        ]);
    }
}
