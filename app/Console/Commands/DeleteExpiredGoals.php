<?php

namespace App\Console\Commands;
use App\Models\Goal;
use Illuminate\Console\Command;
use Carbon\Carbon;

class DeleteExpiredGoals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'goals:delete-expired';
    protected $description = 'Deletes expired goals';

    /**
     * The console command description.
     *
     * @var string
     */


    /**
     * Execute the console command.
     */
    public function handle()
    {

        $deletedGoals = Goal::where('expiring_date', '<', Carbon::now())->delete();

        $this->info($deletedGoals . ' expired goals have been deleted successfully.');
    }
}
