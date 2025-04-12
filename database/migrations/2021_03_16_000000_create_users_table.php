<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email',45)->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('first_name',45)->nullable();
            $table->string('last_name',45)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('image')->nullable();
            $table->string('theme_color')->default('at-blue')->nullable();
            $table->string('macAddress',50)->nullable();
            $table->string('client_app_version',10)->nullable();
            $table->rememberToken()->nullable();
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
        Schema::dropIfExists('users');
    }
}
