<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Breaks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breaks', function(Blueprint $table){
            $table->increments('id');
            $table->string('break_title', 100);
            $table->integer('shift_id')->unsigned()->length(10);

            $table->integer('company_id')->unsigned()->length(10);
            $table->integer('created_by')->unsigned()->length(10);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('shift_id')->references('id')->on('shifts')->nullable();
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
        Schema::dropIfExists('breaks');
    }
}
