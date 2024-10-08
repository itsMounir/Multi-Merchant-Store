<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
class DiscountAdded extends Notification
{
    use Queueable;

    private $supplier;
    private $discounts;

    public function __construct($supplier)
    {
        $this->supplier = $supplier;
     //   $this->discounts = $discounts;
    }


    public function databaseType(object $notifiable): string
    {
        return 'discount';
    }



    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            "supplier"=>$this->supplier,
            'message' => "{$this->supplier->store_name}  قام بإضافة خصم جديد.",
            'image'=>$this->supplier->images
        ];
    }
}
