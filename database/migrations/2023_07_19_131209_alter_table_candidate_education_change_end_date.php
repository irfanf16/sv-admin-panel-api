<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCandidateEducationChangeEndDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('candidate_education', 'end_date')){
            Schema::table('candidate_education', function (Blueprint $table) {
                $table->date('end_date')->change();
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
        if(Schema::hasColumn('candidate_education', 'end_date')){
            Schema::table('candidate_education', function (Blueprint $table) {
                $table->integer('end_date')->change();
            });
        }
    }
}
