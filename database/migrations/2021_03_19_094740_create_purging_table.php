<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurgingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purging', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->length(11)->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('last_purging_date')->nullable();
            $table->char('status',255)->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purging');
    }
}
