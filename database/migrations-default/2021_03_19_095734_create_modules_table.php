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
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',45)->nullable();
            $table->string('description',255)->nullable();
            $table->string('url',255)->nullable();
            $table->string('icon',45)->nullable();
            $table->integer('parent_module_id')->nullable();
            $table->integer('module_order')->nullable()->default(null);
            $table->tinyInteger('module_type')->default(1);
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
        Schema::dropIfExists('modules');
    }
};
