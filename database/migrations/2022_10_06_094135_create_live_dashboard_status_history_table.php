<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveDashboardStatusHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_dashboard_status_history', function (Blueprint $table) {
            $table->integer('live_dashboard_id')->comment('Get ID from live_dashboard_tracking table');
            $table->tinyInteger('user_status')->nullable()->comment('1 for Active, 2 for Idle, 3 for absent, 4 On Break, 5 No Status');
            $table->tinyInteger('type')->default(1)->comment('1 for user status, 2 for task status');
            $table->tinyInteger('task_status')->nullable()->comment('Task status according to TTH task status');
            $table->time('start_time');
            $table->time('end_time');
            $table->date('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('live_dashboard_status_history');
    }
}
