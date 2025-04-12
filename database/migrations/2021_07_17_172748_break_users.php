<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BreakUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('break_users', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id')->unsigned()->length(10);
            $table->integer('break_id')->unsigned()->length(10);

            $table->foreign('break_id')->references('id')->on('breaks');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('break_users');
    }
}
