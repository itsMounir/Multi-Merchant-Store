<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class updateStatusBill extends Notification
{
    use Queueable;

    public function __construct(public $supplier, public $status, public $market)
    {
    }

    public function databaseType(object $notifiable): string
    {
        return 'update-bill-status';
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "قام المورد {$this->supplier->store_name} بتغيير حالة الفاتورة إلى {$this->status} لدى الماركت {$this->market->store_name}."
        ];
    }
}
