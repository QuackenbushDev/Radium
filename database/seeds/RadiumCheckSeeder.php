<?php

use Illuminate\Database\Seeder;
use App\RadiusCheck;

class RadiumCheckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\RadiusCheck::class, 500)->create();
    }
}
