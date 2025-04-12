<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveDashboardTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_dashboard_tracking', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('department_id')->unsigned();
            $table->integer('team_id')->unsigned();
            $table->integer('project_id')->unsigned()->nullable();
            $table->integer('task_id')->unsigned()->nullable();
            $table->tinyInteger('task_status')->nullable()->comment('Task status according to TTH task status');
            $table->tinyInteger('user_status')->nullable()->comment('1 for Active, 2 for Idle, 3 for absent, 4 On Break, 5 No Status,');
            $table->bigInteger('login_id')->nullable()->comment('Join table id with live_dashboard_login');
            $table->time('entry_time')->nullable();
            $table->date('entry_date')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('team_id')->references('id')->on('groups');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('task_id')->references('id')->on('activities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('live_dashboard_tracking');
    }
}
