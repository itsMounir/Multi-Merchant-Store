<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bill;
use App\Models\Supplier;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PendingBillNotification;
use App\Services\MobileNotificationServices;
class SendPreparingBillNotification extends Command
{
    protected $signature = 'preparing:send-notification';
    protected $description = 'Notify suppliers about bills that are pending for more than two days.';
    public function handle()
    {
        $notification= new MobileNotificationServices;

        $suppliers = Supplier::has('bills', '>', 0)->get();
        $twoDaysAgo = now()->subDays(3);
        foreach ($suppliers as $supplier) {
            $newInvoicesCount = $supplier->bills()->where('status', 'قيد التحضير')->where('updated_at','<',$twoDaysAgo)->count();
            if ($newInvoicesCount > 0) {

                 $supplier->notify(new PendingBillNotification($newInvoicesCount));
                 $notification->sendNotification($supplier->deviceToken,"فواتير غير مستلمة","لديك {$newInvoicesCount}فواتير غير مستلمة.");

            }
        }

        $this->info('Notifications sent successfully!');
    }
}
