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
        Schema::create('module_features_list', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('module_features_id')->nullable();
            $table->tinyInteger('type')->default('1')->comment('1=info, 2=implementation');
            $table->integer('feature_key')->nullable();
            $table->string('feature_value', 30)->nullable();
            $table->string('feature_label', 50)->nullable();
            $table->tinyInteger('status')->default('1')->comment('1=Active, 0=Inactive');
            $table->text('content')->nullable();
            $table->string('image', 150)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('module_features_list');
    }
};
