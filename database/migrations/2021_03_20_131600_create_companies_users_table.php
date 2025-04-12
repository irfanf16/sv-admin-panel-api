<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->length(10)->unsigned();
            $table->integer('user_id')->length(10)->unsigned();
            $table->integer('profile_id')->length(10)->unsigned();
            $table->string('status',20);
            $table->integer('rate')->length(11)->nullable()->defaualt(0);
            $table->integer('bill')->length(11)->nullable()->defaualt(0);
            $table->integer('weekly_limit')->length(11)->nullable()->defaualt(0);
            $table->tinyInteger('is_deleted')->length(1)->defaualt(0);
            $table->tinyInteger('allow_tracking')->length(4)->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('companies_users');
    }
}
