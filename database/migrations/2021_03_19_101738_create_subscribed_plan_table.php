<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscribedPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribed_plan', function (Blueprint $table) {
            $table->increments('cp_id');
            $table->integer('plan_id')->length(10)->unsigned();
            $table->integer('super_admin_id')->index('state');
            $table->integer('company_id')->length(10)->unsigned();
            $table->dateTime('expiry_date')->nullable();
            $table->timestamp('deleted_at');
            $table->timestamps();

            $table->foreign('plan_id')->references('plan_id')->on('plans');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscribed_plan');
    }
}
