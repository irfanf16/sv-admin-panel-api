<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',45)->nullable();
            $table->char('company_initial',10);
            $table->integer('no_of_employee')->length(11)->default(0);
            // $table->bigInteger('instance_id')->length(20)->unsigned()->default(1);
            $table->integer('super_admin_id')->length(11)->nullable();
            $table->string('logo',100)->nullable();
//            $table->boolean('has_setup')->default(0)->comment('Once user setup the company it will become 1');
            $table->timestamps();
            // $table->foreign('instance_id')->references('id')->on('intances');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
