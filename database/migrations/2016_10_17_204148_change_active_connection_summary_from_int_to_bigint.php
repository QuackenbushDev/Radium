<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeActiveConnectionSummaryFromIntToBigint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('radium_active_connection_summary', function(Blueprint $table) {
            $table->bigInteger('download', false, true)->change();
            $table->bigInteger('upload', false, true)->change();
            $table->bigInteger('total', false, true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('radium_active_connection_summary', function(Blueprint $table) {
            $table->integer('download')->change();
            $table->integer('upload')->change();
            $table->integer('total')->change();
        });
    }
}
