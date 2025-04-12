<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddRemoveNeweColumnsInWebAppTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('web_app_tracking');
        Schema::create('web_app_tracking', function (Blueprint $table) {
            $table->id();
//            $table->integer('company_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('task_id')->nullable();
            $table->integer('project_id')->nullable()->unsigned();
            $table->integer('course_id')->nullable()->unsigned();
            $table->tinyInteger('type')->default(1)->comment('1 for Website , 2 for Apps');
            $table->text('app')->comment(' to store app name (e.g WINDWARD.EXE)');
            $table->text('description')->comment('text to store window title (e.g. Mobile API for settings and MO.docx - Protected View - Word)');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP'));

            //$table->foreign('company_id')->references('id')->on('companies');
            // $table->foreign('user_id')->references('id')->on('users');
//            $table->foreign('task_id')->references('id')->on('activities');
            // $table->foreign('project_id')->references('id')->on('projects');
            // $table->foreign('course_id')->references('id')->on('courses');

        });
        DB::statement("ALTER TABLE web_app_tracking drop primary key, add primary key(id, start_time)");
        DB::statement("CREATE INDEX idx_startend_time_wat on web_app_tracking(start_time,end_time, user_id)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_app_tracking');

    }
}
