<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactInformationColumnsToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->string('department');
            $table->string('company');
            $table->string('home_phone');
            $table->string('work_phone');
            $table->string('mobile_phone');
            $table->text('address');
            $table->text('notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('department');
            $table->dropColumn('company');
            $table->dropColumn('home_phone');
            $table->dropColumn('work_phone');
            $table->dropColumn('mobile_phone');
            $table->dropColumn('address');
            $table->dropColumn('notes');
        });
    }
}
