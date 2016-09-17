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
        Commands\DictionaryCompile::class,
        Commands\OperatorEmailSummary::class,
        Commands\UserEmailSummary::class,
        Commands\AccountingProcess::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('operator:emailsummary --type=weekly')
            ->cron('0 0 * * 7');
        $schedule->command('operator:emailsummary --type=monthly')
            ->monthlyOn(1);
    }
}
