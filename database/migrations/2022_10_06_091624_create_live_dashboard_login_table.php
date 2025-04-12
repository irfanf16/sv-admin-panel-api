<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveDashboardLoginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_dashboard_login', function (Blueprint $table) {
            $table->id();
            //$table->integer('company_id')->unsigned()->comment('Foreign Key with Company table Primary ID');
            //$table->integer('user_id')->unsigned()->comment('Foreign Key with User table Primary ID');
            //$table->time('login_time')->nullable();
            //$table->time('logout_time')->nullable();
            //$table->softDeletes();
            //$table->timestamps();

            //$table->foreign('company_id')->references('id')->on('companies');
            //$table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('live_dashboard_login');
    }
}
