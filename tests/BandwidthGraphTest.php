<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;
use App\Utils\Bandwidth;
use App\BandwidthSummary;

class BandwidthGraphTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp() {
        parent::setUp();
        $this->createBandwidth();
        $this->createBandwidth(true);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testMonthlyUsage()
    {
        $data = [
            'download' => array_pad([], 12, 18.62),
            'upload'   => array_pad([], 12, 0.18)
        ];
        $usage = BandwidthSummary::bandwidthUsage("month", 2016);

        foreach ($data['download'] as $i => $download) {
            $match = ($data['download'][$i] === $usage['download'][$i] && $data['upload'][$i] === $usage['upload'][$i]);
            $this->assertTrue($match);
        }
    }

    private function createBandwidth($closeConnection = false) {
        for ($i = 1; $i <= 12; $i++) {
            $start = Carbon::createFromFormat("Y-m-d", "2016-" . $i . "-01");
            Carbon::setTestNow($start);

            $connection = factory(App\RadiusAccount::class, 1)
                ->states('open')
                ->create([
                    'acctinputoctets' => 100000000,
                    'acctoutputoctets' => 10000000000,
                    'acctstarttime' => $start->toDateTimeString(),
                ]);

            if ($closeConnection) {
                $stop = Carbon::createFromFormat("Y-m-d", "2016-" . $i . "-02");
                $connection->acctstoptime = $stop->toDateTimeString();
                $connection->save();
            }

            Bandwidth::process();
        }
    }
}
