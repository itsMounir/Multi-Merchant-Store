<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class Reject extends Notification
{



    public function __construct(public  $supplier,public $rejection_reason,public $market )
    {

    }
    public function databaseType(object $notifiable): string
    {
        return 'reject-bill-dash';
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "لقد رفض الماركت '{$this->market->store_name}' استلام الفاتورة من عند المورد '{$this->supplier->store_name}'   بسبب:   '{$this->rejection_reason}'.",
        ];
    }

}
