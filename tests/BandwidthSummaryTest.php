<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\ActiveConnectionSummary;
use App\BandwidthSummary;
use App\Nas;
use App\Utils\Bandwidth;
use Carbon\Carbon;

class BandwidthSummaryTest extends TestCase
{
    use DatabaseTransactions;

    private $startDate;

    public function setUp()
    {
        parent::setUp();

        $this->startDate = Carbon::create(2016, 9, 29, 0, 0, 0);
        Carbon::setTestNow($this->startDate);
    }

//    public function testOpenConnectionProcessing() {
//        $openConnections = factory(App\RadiusAccount::class, 2)
//            ->states('open')
//            ->create();
//
//        for ($i=0; $i <= 4; $i++) {
//            $this->increaseUsage($openConnections);
//            $this->startDate->addHours(12);
//            Carbon::setTestNow($this->startDate);
//
//            Bandwidth::processOpenConnections();
//        }
//
//        foreach($openConnections as $connection) {
//            $usage = ActiveConnectionSummary::getConnectionUsage($connection->radacctid);
//
//            $this->assertEquals(
//                $connection->acctoutputoctets,
//                $usage->download
//            );
//
//            $this->assertEquals(
//                $connection->acctinputoctets,
//                $usage->upload
//            );
//
//            $total = $connection->acctoutputoctets + $connection->acctinputoctets;
//            $this->assertEquals(
//                $total,
//                $usage->total
//            );
//        }
//    }
//
//    public function testClosedConnectionProcessing() {
//        $closedConnections = factory(App\RadiusAccount::class, 2)
//            ->create();
//
//        Bandwidth::processClosedConnections();
//
//        foreach ($closedConnections as $connection) {
//            $nas = Nas::where('nasname', $connection->nasipaddress)->first();
//            $total = $connection->acctinputoctets + $connection->acctoutputoctets;
//            $usage = BandwidthSummary::getConnectionForDate(
//                $connection->username,
//                $nas->id,
//                $connection->acctstoptime
//            );
//
//            $this->assertEquals($connection->acctoutputoctets, $usage->download);
//            $this->assertEquals($connection->acctinputoctets, $usage->upload);
//            $this->assertEquals($total, $usage->total);
//        }
//    }

    public function testClosedConnectionProcessingWithActiveConnectionSummaries() {
        $connections = factory(App\RadiusAccount::class, 2)
            ->states('open')
            ->create();

        for ($i=0; $i <= 8; $i++) {
            $this->increaseUsage($connections);
            $this->startDate->addHours(12);
            Carbon::setTestNow($this->startDate);

            Bandwidth::processOpenConnections();
        }

        foreach($connections as $connection) {
            $connection->acctstoptime = Carbon::now()->toDateString();
            $connection->save();
        }

        Bandwidth::processClosedConnections();
    }

    private function increaseUsage(&$connections) {
        foreach ($connections as $connection) {
            $connection->acctoutputoctets += rand(1000000000, 10000000000);
            $connection->acctinputoctets += rand(100000000, 900000000);
            $connection->save();
        }
    }
}
