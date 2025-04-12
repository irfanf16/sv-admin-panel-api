<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTableQuizAttemptsStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('quiz_attempts_status', 'quiz_completion') === false){
            Schema::table('quiz_attempts_status', function (Blueprint $table) {
                $table->integer('quiz_completion')->default(0)->after('course_batch_id');
                $table->time('completion_time')->default('00:00:00')->after('quiz_completion');
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
        if (Schema::hasColumn('quiz_attempts_status', 'quiz_completion')) {
            Schema::table('quiz_attempts_status', function (Blueprint $table) {
                $table->dropColumn('quiz_completion');
                $table->dropColumn('completion_time');
            });
        }
    }
}
