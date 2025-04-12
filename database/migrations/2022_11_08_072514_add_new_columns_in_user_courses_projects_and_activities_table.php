<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsInUserCoursesProjectsAndActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_activities', function (Blueprint $table) {
            $table->integer('profile_id')->nullable()->default(0)->after('user_id');
            $table->integer('team_id')->nullable()->default(0)->after('profile_id');
        });
        Schema::table('user_projects', function (Blueprint $table) {
            $table->integer('team_id')->nullable()->default(0)->after('profile_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('user_projects', 'team_id')) {
            Schema::table('user_projects', function (Blueprint $table)  {
                $table->dropColumn('team_id');
            });
        }
        $dropped_columns = ['profile_id','team_id'];
        foreach ($dropped_columns  as $column){
            if (Schema::hasColumn('user_activities', $column)) {
                Schema::table('user_activities', function (Blueprint $table) use($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
}
