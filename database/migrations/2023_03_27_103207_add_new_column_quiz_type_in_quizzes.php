<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnQuizTypeInQuizzes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('quizzes', 'quiz_type')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->tinyInteger('quiz_type')->default(1)->comment('1 = Quiz, 2 = Survey');
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
        if (Schema::hasColumn('quizzes', 'quiz_type')) {
            Schema::table('quizzes', function (Blueprint $table) {
                $table->dropColumn('quiz_type');
            });
        }
    }
}
