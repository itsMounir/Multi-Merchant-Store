<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bill;
use App\Models\Supplier;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PendingBillNotification;

class SendPreparingBillNotification extends Command
{
    protected $signature = 'preparing:send-notification';
    protected $description = 'Notify suppliers about bills that are pending for more than two days.';

    public function handle()
    {
        $suppliers = Supplier::has('bills', '>', 0)->get();
        $twoDaysAgo = now()->subSecond(3);
        foreach ($suppliers as $supplier) {
            $newInvoicesCount = $supplier->bills()->where('status', 'قيد التحضير')->where('updated_at','<',$twoDaysAgo)->count();
            if ($newInvoicesCount > 0) {

                 $supplier->notify(new PendingBillNotification($newInvoicesCount));
            }
        }

        $this->info('Notifications sent successfully!');
    }
}
