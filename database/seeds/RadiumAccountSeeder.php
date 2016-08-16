<?php

use Illuminate\Database\Seeder;
use App\RadiusAccount;
use App\RadiusPostAuth;

class RadiumAccountSeeder extends Seeder {
    private function generateRandomString($length) {
        // Copyright Stephen Watkins
        // Source URL: http://stackoverflow.com/questions/4356289/php-random-string-generator

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function run() {
        $userNames = [
            'ferrydenpark',
            'oakden2@seftonpark',
            'goonwood@seftonpark',
            'yourdc@seftonpark',
            'prospect@seftonpark'
        ];

        for ($month = 1; $month <= 12; $month++) {
            for ($i = 1; $i <= 27; $i++) {
                foreach ($userNames as $userName) {
                    // @TODO: Make this a bit more random...
                    $randomTimeStart = new DateTime('2016-' . $month . '-' . $i . ' 13:00:00');
                    $randomTimeStop = new DateTime('2016-' . $month . '-' . $i . ' ' . rand(14, 16) . ':00:00');

                    RadiusAccount::create([
                        'acctsessionid'      => $this->generateRandomString(8),
                        'acctuniqueid'       => $this->generateRandomString(16),
                        'username'           => $userName,
                        'nasipaddress'       => '10.114.4.1',
                        'nasportid'          => rand(15000000, 16000000),
                        'nasporttype'        => 'Virtual',
                        'acctstarttime'      => $randomTimeStart,
                        'acctstoptime'       => $randomTimeStop,
                        'acctsessiontime'    => (120 * 60),
                        'acctinputoctets'    => rand(100000000000, 14000000000),
                        'acctoutputoctets'   => rand(1000000, 1000000),
                        'calledstationid'    => '10.114.41.1',
                        'acctterminatecause' => 'User-Request',
                        'servicetype'        => 'Framed-User',
                        'framedprotocol'     => 'PPP',
                        'framedipaddress'    => '0.0.0.0',
                        'acctstartdelay'     => 0,
                        'acctstopdelay'      => 0
                    ]);
                }
            }
        }
    }
}