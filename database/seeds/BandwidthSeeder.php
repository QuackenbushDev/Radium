<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Utils\Bandwidth;

class BandwidthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $start = Carbon::createFromDate(date("Y"), 1, 1);

        for ($i = 1; $i <= 345; $i++) {
            Carbon::setTestNow($start);

            factory(App\RadiusAccount::class, 1)
                ->create([
                    'acctinputoctets'  => rand(100000000, 400000000),
                    'acctoutputoctets' => rand(1000000000, 9000000000),
                    'acctstarttime'    => $start->toDateTimeString(),
                    'acctstoptime'     => $start->toDateTimeString()
                ]);

            $start->addDay();
        }

        Bandwidth::process();
    }
}
