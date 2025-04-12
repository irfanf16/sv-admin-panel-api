<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BreakSlots extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('break_slots', function(Blueprint $table){
            $table->increments('id');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('break_id')->unsigned()->length(10);

            $table->foreign('break_id')->references('id')->on('breaks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('break_slots');
    }
}
