<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompletionTimeInQuizAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('quiz_attempts', 'completion_time')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                $table->time('completion_time')->default('00:00:00');
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
        if (Schema::hasColumn('quiz_attempts', 'completion_time')) {
            Schema::table('quiz_attempts', function (Blueprint $table) {
                $table->dropColumn('completion_time');
            });
        }
    }
}
