<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimelogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timelogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image',255)->nullable();
            $table->string('thumbnail',255)->nullable();
            // $table->string('company_id ',45)->nullable(); Pending due to change
            $table->integer('project_id')->length(10)->unsigned();
            $table->integer('activity_id')->length(10)->unsigned();
            $table->integer('user_id')->length(10)->unsigned();
            $table->integer('key_count')->length(11)->nullable();
            $table->integer('click_count')->length(11)->nullable();
            $table->integer('click_movement')->length(11)->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->tinyInteger('status')->length(4)->nullable();
            $table->tinyInteger('deleted')->length(4)->nullable()->default(0);
            $table->float('activity_level')->default(0)->nullable();
            $table->text('description')->nullable();
            $table->string('type')->default('A')->comment('A = auto time tracking,M = manual time tracking');
            $table->integer('created_by')->length(10)->unsigned();
            $table->timestamp('created_at')->nullable();


            // $table->foreign('created_by')->references('id')->on('users');
            // $table->foreign('project_id')->references('id')->on('projects');
            // $table->foreign('activity_id')->references('id')->on('activities');
            // $table->foreign('user_id')->references('id')->on('users');
        });

        DB::statement('alter table timelogs drop primary key, add primary key(id, start_time)');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timelogs');
    }
}
