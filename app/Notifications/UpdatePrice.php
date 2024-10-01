<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdatePrice extends Notification
{



    public function __construct(public $product,public  $supplier)
    {

    }
    public function databaseType(object $notifiable): string
    {
        return 'update_price';
    }

    public function via($notifiable)
    {
        return ['database'];
    }
    public function toArray($notifiable)
    {
        return [
            'message' => 'تم تعديل سعر المنتج ' . $this->product->name . ' من عند المورد ' . $this->supplier->store_name . '.',
            'image'=>$this->supplier->images
        ];
    }

}
