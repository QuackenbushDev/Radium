<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRadiumRealmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radium_realm', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type');
            $table->string('authhost');
            $table->string('accthost');
            $table->string('secret')->nullable();
            $table->string('ldflag')->nullable();
            $table->boolean('nostrip')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('radium_realm');
    }
}
