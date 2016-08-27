<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendOperatorSummary extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function handle(User $user, $bandwidthGraph, $connectionGraph, $dateStart, $dateStop)
    {
        $data =             [
            'user'            => $user,
            'dateStart'       => $dateStart,
            'dateStop'        => $dateStop,
            'bandwidthGraph'  => $bandwidthGraph,
            'connectionGraph' => $connectionGraph,
            'title'           => 'Monthly Usage summary for ' . $dateStart . '-' . $dateStop,
        ];

        Mail::send('emails.summary.usage',$data, function($m) use ($user) {
            $m->to($user->email);
        });
    }
}
