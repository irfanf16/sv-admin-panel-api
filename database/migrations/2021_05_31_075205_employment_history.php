<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmploymentHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employement_history', function (Blueprint $table) {
            
            $table->increments('id');
            $table->integer('user_id')->length(10)->unsigned();
            $table->date('job_start');
            $table->date('job_end');
            $table->string('company_name', 50);
            $table->string('resign_reason', 200);
            $table->integer('salary');
            $table->string('role', 20);
            $table->string('employer_contact', 15)->nullable();
            $table->longText('additinal_info')->nullable();
            $table->timestamps();

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
        Schema::dropIfExist('employement_history');
    }
}
