<?php

namespace App\Jobs;

use App\User;
use App\Utils\Graph;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Cache;
use Mail;
use DateTime;
use DateInterval;

class SendOperatorSummary extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $operator;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @param User $operator
     * @param string $type
     * @return void
     */
    public function __construct(User $operator, $type)
    {
        $this->operator = $operator;
        $this->type = $type;
    }

    public function handle()
    {
        $startDate = new DateTime();
        $startDate->sub(new DateInterval('P7D'));

        $bandwidthGraph = Cache::get('SendOperatorSummary.' . $this->type . '.bandwidthGraph');
        if ($bandwidthGraph === null) {
            if ($this->type === 'month') {
                $bandwidthGraph = Graph::createBandwidthGraphPNG('day', date('m'));
                Cache::put('SendOperatorSummary.' . $this->type . '.bandwidthGraph', 30);
            } else {
                $bandwidthGraph = Graph::createBandwidthGraphPNG('week', $date);
                Cache::set('SendOperatorSummary.' . $this->type . '.bandwidthGraph');
            }
        }

        $connectionGraph = Cache::get('SendOperatorSummary.' . $this->type . '.connectionGraph');
        if ($connectionGraph === null) {
            if ($this->type === 'month') {
                $connectionGraph = Graph::createConnectionGraphPNG('day', date('m'));
                Cache::put('SendOperatorSummary.' . $this->type . '.connectionGraph', 30);
            } else {
                $connectionGraph = Graph::createBandwidthGraphPNG('week', $date);
                Cache::set('SendOperatorSummary.' . $this->type . '.connectionGraph');
            }
        }

        if ($this->type === 'month') {
            $title = 'Monthly summary for ' . date('M');
        } else {
            $title = 'Weekly summary for ' . date('d') . '-' . date('d');
        }

        $operator = $this->operator;
        $data = [
            'title' => $title,
            'bandwidthGraph' => $bandwidthGraph,
            'connectionGraph' => $connectionGraph,
            'operator' => $operator,
        ];

        Mail::send('email.summary.operator-usage', $data, function($m) use ($operator) {
            $m->to($operator->email);
            $m->subject('Radium System Report');
        });
    }
}
