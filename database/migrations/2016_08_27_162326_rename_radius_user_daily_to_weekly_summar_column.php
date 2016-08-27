<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameRadiusUserDailyToWeeklySummarColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('radius_account_info', function ($table) {
            $table->renameColumn('enable_daily_summary', 'enable_weekly_summary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->renameColumn('enable_weekly_summary', 'enable_daily_summary');
        });
    }
}
