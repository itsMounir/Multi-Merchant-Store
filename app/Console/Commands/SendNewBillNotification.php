<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{
Supplier
};
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewBillNotification;
use App\Traits\FirebaseNotification;

class SendNewBillNotification extends Command
{ 
    use FirebaseNotification ;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'NewBill:send-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $suppliers = Supplier::has('bills', '>', 0)->get();

        foreach ($suppliers as $supplier) {
            $newInvoicesCount = $supplier->bills()->where('status', 'جديد')->count();
            if ($newInvoicesCount > 0) {

                 $supplier->notify(new NewBillNotification($newInvoicesCount));
                 $this->sendNotification($supplier->deviceToken,"فاتورة جديدة","لديك {$newInvoicesCount}فواتير جديدة.");
            }
        }

        $this->info('Notifications sent successfully!');
    }
}
