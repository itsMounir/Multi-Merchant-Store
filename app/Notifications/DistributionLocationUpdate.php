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
        return ['database'];
    }

    public function databaseType(object $notifiable): string
    {
        return 'update-Distribution-Location';
    }

    public function toDatabase($notifiable)
    {
        return [
            'supplier_name' =>" {$this->supplier->first_name} {$this->supplier->middle_name} {$this->supplier->last_name}" ,
            'new_locations' => $this->newLocations
        ];
    }


}
