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
            $table->integer('nas_id')
                ->unsigned()
                ->foreign('nas', 'id');
            $table->string('username');
            $table->date('date');
            $table->integer('download');
            $table->integer('upload');
            $table->integer('total');
            $table->integer('connection_count');
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
