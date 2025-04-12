<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CourseTimelogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timelogs', function(Blueprint $table){
            $table->integer('course_id')->length(10)->unsigned()->nullable()->after('activity_id');
            $table->integer('article_id')->length(10)->unsigned()->nullable()->after('course_id');
            $table->integer('quiz_id')->length(10)->unsigned()->nullable()->after('article_id');

            // $table->foreign('course_id')->references('id')->on('courses');
            // $table->foreign('article_id')->references('id')->on('articles');
            // $table->foreign('quiz_id')->references('id')->on('quizzes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('timelogs', function(Blueprint $table){
            $table->dropColumn(['course_id', 'article_id', 'quiz_id']);
        });
    }
}
