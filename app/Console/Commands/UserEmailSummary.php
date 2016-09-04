<?php namespace App\Console\Commands;

use App\RadiusAccountInfo;
use App\Jobs\SendUserSummary;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class UserEmailSummary extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:sendsummary
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
            $users = RadiusAccountInfo::where('enable_weekly_summary', true)->get();
        } elseif ($type === 'month') {
            $users = RadiusAccountInfo::where('enable_monthly_summary', true)->get();
        } else {
            echo "Invalid type detected. Available options are weekly and monthly.";
            return;
        }

        foreach ($users as $user) {
            $this->dispatch(new SendUserSummary($user, $type));
        }

        echo "Your job has been queued!";
    }
}
