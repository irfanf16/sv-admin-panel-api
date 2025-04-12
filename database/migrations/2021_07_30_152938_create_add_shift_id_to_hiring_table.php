<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddShiftIdToHiringTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidate_info', function(Blueprint $table){
            $table->integer('shift_id')->length(10)->unsigned()->after('apparaisal_eligibility')->nullable();

            $table->foreign('shift_id')->references('id')->on('shifts');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
