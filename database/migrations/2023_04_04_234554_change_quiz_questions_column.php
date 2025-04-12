<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeQuizQuestionsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quiz_questions', function (Blueprint $table) {
           $table->longText('question')->change();
        });
        if (!Schema::hasColumn('quiz_questions', 'quiz_type')) {
            Schema::table('quiz_questions', function (Blueprint $table) {
                $table->string('score',100)->nullable();
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
        if (Schema::hasColumn('quiz_questions', 'score')) {
            Schema::table('quiz_questions', function (Blueprint $table) {
                $table->dropColumn('score');
            });
        }
    }
}
