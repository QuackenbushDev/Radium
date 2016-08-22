<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ProxyWrite::class,
        Commands\OperatorDailyEmailSummary::class,
        Commands\OperatorMonthlyEmailSummary::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('email:dailysummary')
            ->daily();
        $schedule->command('email:monthlysummary')
            ->daily();

        // $schedule->command('inspire')
        //          ->hourly();
    }
}
