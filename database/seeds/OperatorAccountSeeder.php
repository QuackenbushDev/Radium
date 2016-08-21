<?php

use Illuminate\Database\Seeder;
use App\User;

class OperatorAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => "admin@radium.radium",
            'password' => bcrypt("radiumadmin"),
            'name' => "Radium Administrator"
        ]);

        echo "Successfully created radium demo account.\n";
        echo "You may login with the email: admin@radium.radium and the password: radiumadmin.\r\n";
    }
}
