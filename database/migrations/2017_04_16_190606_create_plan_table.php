<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('limit');
            $table->boolean('throttle_on_limit');
            $table->integer('throttle_speed_on_limit');
            $table->boolean('disable_on_limit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('plan');
    }
}
