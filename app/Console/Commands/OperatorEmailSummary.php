<?php namespace App\Console\Commands;

use App\User;
use App\Jobs\SendOperatorSummary;
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
     * @return void
     */
    public function handle()
    {
        $type = $this->option('type');

        if ($type === null) {
            echo "Please provide a type when running the summary command";
            return;
        }

        if ($type === 'week') {
            $operators = User::where('enable_weekly_summary', true)->get();
        } elseif ($type === 'month') {
            $operators = User::where('enable_monthly_summary', true)->get();
        } else {
            echo "Invalid type detected. Available options are weekly and monthly.";
            return;
        }

        foreach ($operators as $operator) {
            $this->dispatch(new SendOperatorSummary($operator, $type));
        }

        echo "Your job has been queued!";
    }
}
