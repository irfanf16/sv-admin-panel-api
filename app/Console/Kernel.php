<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\WebAppTrackingMerging;
use App\Console\Commands\LiveDashboardCommand;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        WebAppTrackingMerging::class,
        LiveDashboardCommand::class
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // For the middle of the trial reminder: 
        $schedule->call('App\Services\EmailJobsService@midTrialEmail')->dailyAt('00:00'); //->everyMinute();
        $schedule->call('App\Services\EmailJobsService@lastDayTrialEmail')->dailyAt('00:00');


        // Perge Databases for companies
        $schedule->call('App\Services\PurgeCompanies@purge')->dailyAt('00:00');

        // Grace Period
        $schedule->call('App\Services\GracePeriod@index')->dailyAt('00:00');

        // run command every hour
        // $schedule->command('command:web-app-tracking-merging')->everyMinute();
        // $schedule->command('app:web-app-tracking-merging')->everyThirtyMinutes();
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
