<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnToUserCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('user_courses', 'assigned_by') === false){
            Schema::table('user_courses', function (Blueprint $table) {
                $table->integer('assigned_by')->default(0)->after('user_id');
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
        if(Schema::hasColumn('user_courses', 'assigned_by')){
            Schema::table('user_courses', function (Blueprint $table) {
                $table->dropColumn('assigned_by');
            });
        }
    }
}
