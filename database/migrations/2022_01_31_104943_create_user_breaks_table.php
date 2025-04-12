<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBreaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_breaks', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->length(10)->unsigned();
            $table->integer('scheduled_by')->length(10)->unsigned();
            $table->time('break_start');
            $table->time('break_end');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
//            $table->foreign('scheduled_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_breaks');
    }
}
