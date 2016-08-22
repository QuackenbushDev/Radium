<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OperatorMonthlyEmailSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'operator:monthlyemailsummary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'E-Mails the monthly usage summary to all operators';

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
