<?php

namespace App\Jobs;

use App\User;
use App\Nas;
use App\Utils\Graph;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Cache;
use Mail;
use DateTime;

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
        $startDate->modify('-8 days');
        $startDate = $startDate->format('Y-m-d');
        $month = date('m') - 1;

        $bandwidthGraph = Cache::get('SendOperatorSummary.' . $this->type . '.bandwidthGraph');
        if ($bandwidthGraph === null) {
            if ($this->type === 'month') {
                $bandwidthGraph = Graph::createBandwidthGraphPNG('day', $month);
                Cache::put('SendOperatorSummary.' . $this->type . '.bandwidthGraph', 30);
            } else {
                $bandwidthGraph = Graph::createBandwidthGraphPNG('week', $startDate);
                Cache::put('SendOperatorSummary.' . $this->type . '.bandwidthGraph', 30);
            }
        }

        $connectionGraph = Cache::get('SendOperatorSummary.' . $this->type . '.connectionGraph');
        if ($connectionGraph === null) {
            if ($this->type === 'month') {
                $connectionGraph = Graph::createConnectionGraphPNG('day', $month);
                Cache::put('SendOperatorSummary.' . $this->type . '.connectionGraph', 30);
            } else {
                $connectionGraph = Graph::createConnectionGraphPNG('week', $startDate);
                Cache::put('SendOperatorSummary.' . $this->type . '.connectionGraph', 30);
            }
        }

        $nasList = Nas::all();
        $nasGraphs = [];

        foreach($nasList as $nas) {
            $graph = Cache::get('SendOperatorSummary.' . $this->type . '.nas.' . $nas->nasname);

            if ($graph === null) {
                if ($this->type === 'month') {
                    $nasGraphs[] = Graph::createBandwidthGraphPNG('day', $month, null, $nas->nasname);
                    Cache::put('SendOperatorSummary.' . $this->type . '.bandwidthGraph', 30);
                } else {
                    $nasGraphs[] = Graph::createBandwidthGraphPNG('week', $startDate, null, $nas->nasname);
                    Cache::put('SendOperatorSummary.' . $this->type . '.bandwidthGraph', 30);
                }
            }
        }

        if ($this->type === 'month') {
            $month = new DateTime(date("Y") . '-' . $month . '-01');
            $title = 'Monthly summary for ' . $month->format("M");
        } else {
            $title = 'Summary for the week of ' . $startDate;
        }

        $operator = $this->operator;
        $data = [
            'title' => $title,
            'bandwidthGraph' => $bandwidthGraph,
            'connectionGraph' => $connectionGraph,
            'nasGraphs' => $nasGraphs,
            'nasList' => $nasList,
            'operator' => $operator,
        ];

        Mail::send('email.summary.operator-usage', $data, function($m) use ($operator) {
            $m->to($operator->email);
            $m->subject('Radium System Report');
        });
    }
}
