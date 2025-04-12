<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CandidateInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->length(10)->unsigned();
            $table->string('father_name', 30);
            $table->string('personal_email', 30);
            $table->string('employee_photo', 100)->nullable();
            $table->string('cnic', 15);
            $table->string('primary_contact', 13);
            $table->string('secondary_contact', 13)->nullable();
            $table->string('permanent_address', 100);
            $table->string('current_address', 100)->nullable();
            $table->string('country', 20);
            $table->string('state', 20)->nullable();
            $table->string('city', 20);
            $table->string('zip_code', 6);
            $table->tinyInteger('willing_to_relocate')->nullable();
            $table->string('preffered_contact_mode', '20');
            $table->string('employee_code', 10)->nullable();
            $table->string('alias_name', 20)->nullable();
            $table->string('designation', 20)->nullable();
            $table->integer('department')->unsigned()->nullable();
            $table->string('temp_address', 200)->nullable();
            $table->string('work_phone', 15)->nullable();
            $table->string('employment_status', 20)->nullable();
            $table->date('offer_sending_date')->nullable();
            $table->date('offer_acceptance_date')->nullable();
            $table->date('joining_date')->nullable();
            $table->date('exit_date')->nullable();
            $table->string('apparaisal_eligibility', 20)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('department')->references('id')->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidate_info');
    }
}
