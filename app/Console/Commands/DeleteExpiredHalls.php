<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Hall;
use Carbon\Carbon;

class DeleteExpiredHalls extends Command
{
    protected $signature = 'halls:delete-expired';
    protected $description = 'Delete halls whose time has ended';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deleted = Hall::where(function ($query) {
            $query->where('date', '<', Carbon::today())
                  ->orWhere(function ($q) {
                      $q->where('date', Carbon::today())
                        ->where('end_time', '<', Carbon::now()->format('H:i:s'));
                  });
        })->delete();

        $this->info("Deleted {$deleted} expired halls");
    }
}
