<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_articles', function(Blueprint $table){
            $table->increments('id');
            $table->integer('article_id')->unsigned()->nullable()->default(null);
            $table->integer('quiz_id')->unsigned()->nullable()->default(null);
            $table->integer('course_id')->unsigned();
            $table->integer('article_order')->unsigned();
            $table->timestamps();

            $table->foreign('article_id')->references('id')->on('articles');
            $table->foreign('quiz_id')->references('id')->on('quizzes');
            $table->foreign('course_id')->references('id')->on('courses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_articles');
    }
}
