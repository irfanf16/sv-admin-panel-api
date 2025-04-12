<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimelogsIdleTimeHistoryTable extends Migration
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timelogs_idle_time_history', function (Blueprint $table) {
            $table->id();
            $table->integer('timelogs_id')->comment('save timelog id');
            $table->integer('company_setting_idle_time')->comment('save company default idle time');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timelogs_idle_time_history');
    }
}
