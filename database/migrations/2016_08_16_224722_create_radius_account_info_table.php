<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRadiusAccountInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radius_account_info', function(Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('company');
            $table->string('home_phone');
            $table->string('mobile_phone');
            $table->string('office_phone');
            $table->text('address');
            $table->text('notes');
            $table->boolean('enable_portal');
            $table->boolean('enable_password_resets');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('radius_account_info');
    }
}
