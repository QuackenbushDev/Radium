<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResetPasswordTokenToRadiusAccountInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('radius_account_info', function(Blueprint $table) {
            $table->string('reset_password_token');
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
            $table->dropColumn('reset_password_token');
        });
    }
}
