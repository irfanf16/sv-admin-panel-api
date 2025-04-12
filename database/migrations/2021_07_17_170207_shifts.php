<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Shifts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shifts', function(Blueprint $table){
            $table->increments('id');
            $table->string('shift_name', 70);
            $table->integer('shift_manager')->unsigned()->length(10);
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('company_id')->unsigned()->length(10);
            $table->integer('created_by')->unsigned()->length(10);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('shift_manager')->references('id')->on('users')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shifts');
    }
}
