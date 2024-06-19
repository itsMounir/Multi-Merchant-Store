<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class BillPreparingMarket extends Notification
{



    public function __construct(public $bill,public  $supplier)
    {

    }
    public function databaseType(object $notifiable): string
    {
        return 'preparing-bill-market';
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'bill_id' => $this->bill->id,
            'supplier_name' => $this->supplier->store_name,
            'message' => 'الفاتورة من المورد ' . $this->supplier->store_name . ' أصبحت قيد التحضير.'
        ];
    }
}
