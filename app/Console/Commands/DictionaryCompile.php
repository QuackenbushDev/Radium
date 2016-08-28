<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;

class DictionaryCompile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dictionary:compile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the dictionary table from source dictionary files.';

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
        $startDate = new \DateTime();
        $startDate->sub(new \DateInterval('P7D'));
        $type = 'month';

        $bandwidthGraph = \Cache::get('SendOperatorSummary.' . $type . '.bandwidthGraph');
        if ($bandwidthGraph === null) {
            if ($type === 'month') {
                $bandwidthGraph = \App\Utils\Graph::createBandwidthGraphPNG('day', date('m'));
                \Cache::put('SendOperatorSummary.' . $type . '.bandwidthGraph', 30);
            }
        }

        $connectionGraph = \Cache::get('SendOperatorSummary.' . $type . '.connectionGraph');
        if ($connectionGraph === null) {
            if ($type === 'month') {
                $connectionGraph = \App\Utils\Graph::createBandwidthGraphPNG('day', date('m'));
                \Cache::put('SendOperatorSummary.' . $type . '.connectionGraph', 30);
            }
        }

        echo $bandwidthGraph;
        echo "\r\n\r\n\r\n";
        echo $connectionGraph;
        die();

        $startTime = microtime(true);
        Artisan::call('db:seed', [
            '--class' => 'DictionarySeeder'
        ]);
        $stopTime = microtime(true);
        echo "Generated dictionary in: " . ($stopTime - $startTime) . "seconds \r\n";
    }
}
