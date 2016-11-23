<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsernameAndNasIdIndexesToActiveConnectionSummary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('radium_active_connection_summary', function(Blueprint $table) {
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
            $table->dropIndex("radium_active_connection_summary_username_nas_id_index");
        });
    }
}
