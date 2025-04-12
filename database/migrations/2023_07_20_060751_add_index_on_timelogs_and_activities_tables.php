<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddIndexOnTimelogsAndActivitiesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        DB::select("ALTER TABLE `activities` DROP INDEX IF EXISTS `idx_activity_due`;");
        DB::select("CREATE INDEX idx_activity_due ON activities ( `start_date` , `due_date` )");
//        DB::select("ALTER TABLE `timelogs` DROP INDEX IF EXISTS `idx_start_end_time`;");
        DB::select("CREATE INDEX idx_start_end_time ON timelogs ( `start_time` , `end_time`, `user_id` )");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
