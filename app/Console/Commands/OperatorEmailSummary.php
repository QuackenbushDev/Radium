<?php namespace App\Console\Commands;

use App\RadiusAccount;
use App\User;
use App\Jobs\SendOperatorSummary;
use App\Utils\Graph;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class OperatorEmailSummary extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'operator:sendsummary
                            {--type= : week or month}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'emails the operators who elect to receive summaries';

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
        $type = $this->option('type');
        $month = date('m');

        if ($type === null) {
            echo "Please provide a type when running the summaries";
            return;
        }

        if ($type === 'week') {
            $timeStart = date('Y') . '-' . $month . '-01';
            $timeStop = date('Y') . '-' . $month . '-31';
            $operators = User::where('enable_weekly_summary', true)->get();
        } elseif ($type === 'month') {
            $operators = User::where('enable_monthly_summary', true)->get();
            $bandwidthGraph = Graph::createBandwidthGraphPNG($type, $month);
            $connectionGraph = Graph::createConnectionGraphPNG($type, $month);

        } else {
            echo "Invalid type detected. Available options are weekly and monthly.";
            return;
        }


        dd($operators, $bandwidthGraph, $connectionGraph);
    }
}
