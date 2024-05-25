<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductSupplier;
use Carbon\Carbon;

class ExpireOffers extends Command
{
    protected $signature = 'offers:expire';
    protected $description = 'Expire product offers and set offer fields to null';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $now = Carbon::now();


        ProductSupplier::where('offer_expires_at', '<', $now)
            ->where('has_offer', true)
            ->update([
                'has_offer' => false,
                'offer_price' => 0,
                'max_offer_quantity' => 0,
                'offer_expires_at' => "9999-1-1",
            ]);

        $this->info('Expired offers have been cleared.');
    }
}
