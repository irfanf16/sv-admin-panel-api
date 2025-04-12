<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanFeaturesDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_features_detail', function (Blueprint $table) {
            $table->increments('pfd_id');
            $table->integer('plan_id')->length(10)->unsigned();
            $table->integer('feature_id')->length(10)->unsigned();
            $table->timestamp('deleted_at');
            $table->timestamps();

            $table->foreign('plan_id')->references('plan_id')->on('plans');
            $table->foreign('feature_id')->references('pf_id')->on('system_features');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_features_detail');
    }
}
