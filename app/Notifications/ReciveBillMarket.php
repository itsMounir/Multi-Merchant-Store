<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReciveBillMarket extends Notification
{



    public function __construct(public  $supplier)
    {

    }
    public function databaseType(object $notifiable): string
    {
        return 'receive-bill';
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'لقد تم توصيل فاتورتك من قبل المورد ' . $this->supplier->store_name,
        ];
    }
}
