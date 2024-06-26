<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\CheckSubscriptionStatus::class,
        Commands\DeleteExpiredGoals::class,
        Commands\ExpireOffers::class,
        Commands\SendNewBillNotification::class,
        Commands\SendPreparingBillNotification::class,
        Commands\Read::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

       $schedule->command('subscriptions:check')->daily();
        $schedule->command('goals:delete-expired')->daily();
        $schedule->command('offers:expire')->everyMinute();
        $schedule->command('NewBill:send-notification')->daily();
        $schedule->command('preparing:send-notification')->daily();
        $schedule->command('read:notifictation')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
