<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnQuizAttemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('quiz_attempts', 'question_id') === false){
            Schema::table('quiz_attempts', function (Blueprint $table) {
                $table->integer('question_id')->nullable()->after('course_batch_id');
                $table->integer('answer_id')->nullable()->after('question_id');
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
        if(Schema::hasColumn('quiz_attempts', 'question_id')){
            Schema::table('quiz_attempts', function (Blueprint $table) {
                $table->dropColumn('question_id');
                $table->dropColumn('answer_id');
            });
        }
    }
}
