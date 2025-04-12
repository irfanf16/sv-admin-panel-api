<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('current_status')->nullable()->default(2)->after('last_active_at');
            $table->integer('current_task_id')->nullable()->after('current_status');
            $table->integer('current_course_id')->nullable()->after('current_task_id');
            $table->timestamp('last_updated')->nullable()->after('current_course_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $dropped_columns = ['current_status', 'current_task_id', 'current_course_id','last_updated'];
          foreach ($dropped_columns  as $column){
            if (Schema::hasColumn('users', $column)) {
                    Schema::table('users', function (Blueprint $table) use($column) {
                        $table->dropColumn($column);
                    });
            }
        }
    }
}
