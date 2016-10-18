<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\RadiusCheck::class, function(Faker\Generator $faker) {
    return [
        'username'  => $faker->userName,
        'attribute' => 'Cleartext-Password',
        'op'        => ':=',
        'value'     => $faker->password(8,20)
    ];
});

$factory->define(App\RadiusAccount::class, function(Faker\Generator $faker) {
    $download = $faker->numberBetween(10000000, 100000000);
    $upload = $faker->numberBetween(1000000, 5000000);

    return [
        'acctsessionid' => $faker->uuid,
        'acctuniqueid' => $faker->uuid,
        'username' => $faker->userName,
        'groupname' => '',
        'realm' => '',
        'nasipaddress' => '10.114.4.1',
        'nasportid' => $faker->numberBetween(1000,1500),
        'nasporttype' => 'Virtual',
        'acctstarttime' => Carbon\Carbon::now(),
        'acctupdatetime' => '',
        'acctstoptime' => Carbon\Carbon::now()->addHours(6),
        'acctinterval' => 1000,
        'acctsessiontime' => 10000,
        'acctauthentic' => '',
        'connectinfo_start' => '',
        'connectinfo_stop' => '',
        'acctinputoctets' => $download,
        'acctoutputoctets' => $upload,
        'calledstationid' => '',
        'callingstationid' => '',
        'acctterminatecause' => '',
        'servicetype' => '',
        'framedprotocol' => '',
        'framedipaddress' => '',
        'processed' => 0
    ];
});

$factory->state(App\RadiusAccount::class, 'open', function($faker) {
    return [
        'acctstoptime' => null
    ];
});