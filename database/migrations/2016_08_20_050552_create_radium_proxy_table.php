<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRadiumProxyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('radium_proxy', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('synchronous')->default(false);
            $table->integer('retry_delay')->nullable();
            $table->integer('retry_count')->nullable();
            $table->integer('dead_time')->nullable();
            $table->boolean('default_fallback')->default(true);
            $table->boolean('post_proxy_authorize')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('radium_proxy');
    }
}
