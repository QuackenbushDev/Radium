<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateIndexToBandwidthSummary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('radium_bandwidth_summary', function(Blueprint $table) {
            $table->index(['date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('radium_bandwidth_summary', function(Blueprint $table) {
            $table->dropIndex("radium_bandwidth_summary_date_index");
        });
    }
}
