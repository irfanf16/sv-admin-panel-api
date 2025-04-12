<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CandidateEducation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_education', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->length(10)->unsigned();
            $table->integer('start_date');
            $table->integer('end_date');
            $table->string('institute_name', 60);
            $table->string('edu_level', 20);
            $table->string('degree_name', 30);
            $table->string('major_subjects', 150);
            $table->float('obtained_marks', 8, 2);
            $table->float('total_marks', 8, 2);
            $table->string('marking_system', 20);
            $table->char('candidate_grade', 3);
            $table->float('percentage', 8, 2);
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
        Schema::dropIfExist('candidate_education');
    }
}
