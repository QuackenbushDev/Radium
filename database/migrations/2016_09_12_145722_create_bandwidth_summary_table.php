<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBandwidthSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radium_bandwidth_summary', function($table) {
            $table->increments('id');
            $table->string('username');
            $table->dateTime('date');
            $table->integer('download');
            $table->integer('upload');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('radium_bandwidth_summary');
    }
}
