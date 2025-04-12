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
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',255);
            $table->string('description')->nullable();
            $table->boolean('proration')->default(true);
            $table->enum('price_type', ['per_unit', 'one_time'])->default('per_unit');
            $table->enum('discount_type', ['percentage', 'fixed'])->default('fixed')->comment('percentage, fixed');
            $table->json('frequency')->nullable();//default(json_encode([]));
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
        Schema::dropIfExists('categories');
    }
};
