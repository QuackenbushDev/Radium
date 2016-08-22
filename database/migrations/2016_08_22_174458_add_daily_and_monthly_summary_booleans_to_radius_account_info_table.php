<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDailyAndMonthlySummaryBooleansToRadiusAccountInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('radius_account_info', function(Blueprint $table) {
            $table->boolean('enable_daily_summary');
            $table->boolean('enable_monthly_summary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('radius_account_info', function(Blueprint $table) {
            $table->dropColumn('enable_daily_summary');
            $table->dropColumn('enable_monthly_summary');
        });
    }
}
