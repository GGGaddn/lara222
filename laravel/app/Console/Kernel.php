<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('wb:incomes')->dailyAt('23:00');
        $schedule->command('wb:orders')->dailyAt('23:00');
        $schedule->command('wb:prices')->dailyAt('23:00');        
        $schedule->command('wb:sales')->dailyAt('23:00');
        $schedule->command('wb:stocks')->dailyAt('23:00');
        $schedule->command('ozon:fbo')->dailyAt('23:00');
        $schedule->command('ozon:fbs')->dailyAt('23:00');

        $schedule->command('wb:report')->dailyAt('23:02');
        $schedule->command('ozon:stocks')->dailyAt('23:05');
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
