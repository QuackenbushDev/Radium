<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsernameAndNasIdIndexesToBandwidthSummary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('radium_bandwidth_summary', function(Blueprint $table) {
            $table->index(['username', 'nas_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('radium_active_connection_summary', function (Blueprint $table) {
            $table->dropIndex("radium_bandwidth_summary_username_nas_id_index");
        });
    }
}
