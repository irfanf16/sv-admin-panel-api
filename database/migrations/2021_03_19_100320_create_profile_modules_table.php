<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_modules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('module_id')->length(10)->unsigned();
            $table->integer('permission_id')->length(10)->unsigned();
            $table->integer('profile_id')->length(10)->unsigned();
            $table->tinyInteger('status')->length(1)->nullable();
            $table->tinyInteger('permanent_disabled')->length(1)->nullable();
            $table->timestamps();

            $table->foreign('module_id')->references('id')->on('modules');
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->foreign('profile_id')->references('id')->on('profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_modules');
    }
}
