<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoEmploymentHistoryToCandidateInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidate_info', function (Blueprint $table) {
            $table->tinyInteger('no_employment_history')->nullable()->after('willing_to_relocate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_info', function (Blueprint $table) {
            $table->dropColumn('no_employment_history');
        });
    }
}
