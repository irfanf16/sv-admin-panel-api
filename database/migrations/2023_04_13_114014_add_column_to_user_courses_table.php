<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToUserCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('user_courses', 'is_removed') === false){
            Schema::table('user_courses', function (Blueprint $table) {
                $table->integer('is_removed')->default(1)->after('course_batch_id')->comment('1=>active, 2=>removed');
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
        if(Schema::hasColumn('user_courses', 'is_removed')){
            Schema::table('user_courses', function (Blueprint $table) {
                $table->dropColumn('is_removed');
            });
        }
    }
}
