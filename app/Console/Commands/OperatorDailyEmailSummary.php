<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OperatorDailyEmailSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'operator:dailyemailsummary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'E-Mails the daily usage summary for operators.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
