<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnQuizQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('quiz_questions', 'status') === false){
            Schema::table('quiz_questions', function (Blueprint $table) {
                $table->integer('status')->default(1)->after('question_tags')->comment('1=>active, 2=>removed');
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
        if(Schema::hasColumn('quiz_questions', 'status')){
            Schema::table('quiz_questions', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
}
