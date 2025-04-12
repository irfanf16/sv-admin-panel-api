<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsTotimelogsIdleHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('timelogs_idle_time_history', 'project_id')){
            Schema::table('timelogs_idle_time_history', function (Blueprint $table) {
                $table->integer('project_id')->nullable()->after('timelogs_id');
            });
        }

        if(!Schema::hasColumn('timelogs_idle_time_history', 'activity_id')){
            Schema::table('timelogs_idle_time_history', function (Blueprint $table) {
                $table->integer('activity_id')->nullable()->after('project_id');
            });
        }

        if(!Schema::hasColumn('timelogs_idle_time_history', 'course_id')){
            Schema::table('timelogs_idle_time_history', function (Blueprint $table) {
                $table->integer('course_id')->nullable()->after('activity_id');
            });
        }
        if(!Schema::hasColumn('timelogs_idle_time_history', 'user_courses_id')){
            Schema::table('timelogs_idle_time_history', function (Blueprint $table) {
                $table->integer('user_courses_id')->nullable()->after('course_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $columns = ['project_id','activity_id', 'course_id', 'user_courses_id'];

        foreach ($columns  as $column){
            if (Schema::hasColumn('timelogs_idle_time_history', $column)) {
                Schema::table('timelogs_idle_time_history', function (Blueprint $table)  use($column){
                    $table->dropColumn($column);
                });
            }
        }
    }
}
