<?php

namespace App\Console\Commands;

use App\Models\Market;
use Illuminate\Console\Command;

class CheckSubscriptionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check markets subscription status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $markets = Market::where('subscription_expires_at', '<=', now())->get();
        foreach ($markets as $market) {
            $market->update(['is_subscribed' => false]);
            $market->save();
            //dd($market);

        }

        $this->info('Subscription status checked and updated successfully.');
    }}
