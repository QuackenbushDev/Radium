<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDictionaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radium_dictionary', function(Blueprint $table) {
            $table->increments('id');
            $table->string('vendor');
            $table->string('attribute');
            $table->string('attribute_type');
            $table->integer('length')->nullable();

            $mysqlVersionQuery = DB::select(DB::raw("select version() as version"));
            $mysqlVersion = explode('-', $mysqlVersionQuery[0]->version)[0];
            if ($mysqlVersion >= '5.7.8') {
                $table->json('values')->nullable();
            } else {
                $table->text('values')->nullable();
            }

            $table->index('vendor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('radium_dictionary');
    }
}
