<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_features_list', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_feature_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('feature_title',255)->nullable();
            $table->json('plan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_features_list');
    }
};
