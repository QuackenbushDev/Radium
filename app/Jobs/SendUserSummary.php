<?php

namespace App\Jobs;

use App\RadiusAccountInfo;
use App\Jobs\Job;
use App\Utils\Graph;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Cache;
use DateTime;
use Mail;

class SendUserSummary extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $user;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(RadiusAccountInfo $user, $type)
    {
        $this->user = $user;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $startDate = new DateTime();
        $startDate->modify('-8 days');
        $startDate = $startDate->format('Y-m-d');
        $month = date('m') - 1;
        $user = $this->user;

        if ($this->type === 'month') {
            $bandwidthGraph = Graph::createBandwidthGraphPNG('day', $month, $user->username);
            $connectionGraph = Graph::createConnectionGraphPNG('day', $month, $user->username);

            $month = new DateTime(date("Y") . '-' . $month . '-01');
            $title = 'Monthly summary for ' . $month->format("M");
        } else {
            $bandwidthGraph = Graph::createBandwidthGraphPNG('week', $startDate, $user->username);
            $connectionGraph = Graph::createConnectionGraphPNG('week', $startDate, $user->username);

            $title = 'Summary for the week of ' . $startDate;
        }

        $data = [
            'title' => $title,
            'bandwidthGraph' => $bandwidthGraph,
            'connectionGraph' => $connectionGraph,
            'user' => $user,
        ];

        Mail::send('email.summary.user-usage', $data, function($m) use ($user) {
            $m->to($user->email);
            $m->subject('Radium System Report');
        });    }
}
