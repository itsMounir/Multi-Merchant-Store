<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Supplier;
use Carbon\Carbon;
class Read extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'read:notifictation';

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
        Supplier::each(function ($supplier) {
            $supplier->notifications()
                     ->whereNull('read_at')
                     ->where('created_at', '<=', Carbon::now()->subMonth())
                     ->update(['read_at' => Carbon::now()]);
        });

    }
}
