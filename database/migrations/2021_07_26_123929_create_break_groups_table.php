<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBreakGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('break_groups', function(Blueprint $table){
            $table->increments('id');
            $table->integer('group_id')->unsigned()->length(10);
            $table->integer('break_id')->unsigned()->length(10);

            $table->foreign('break_id')->references('id')->on('breaks');
            $table->foreign('group_id')->references('id')->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('break_groups');
    }
}
