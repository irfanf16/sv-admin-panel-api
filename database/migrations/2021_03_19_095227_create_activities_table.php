<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description',300)->nullable();
            $table->string('task_status',100);
            $table->tinyInteger('enforce_hours')->length(1)->default(1);
            $table->time('allocated_hours');
            $table->string('type',50);
            $table->integer('activity_recurrence_id')->length(10)->unsigned()->nullable();
            $table->tinyInteger('activity_recurrence')->length(10)->default(1);
            $table->integer('project_id')->length(10)->unsigned()->nullable()->default(null);
            $table->integer('course_id')->length(10)->unsigned()->nullable()->default(null);
            $table->integer('created_by')->length(10)->unsigned();
            $table->integer('updated_by')->length(10)->unsigned();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->integer('cost')->length(11)->default(0);
            $table->integer('bill')->length(11)->default(0);
            $table->timestamps();

            $table->foreign('activity_recurrence_id')->references('id')->on('activities_recurrence');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
