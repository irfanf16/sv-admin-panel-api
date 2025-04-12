<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('super_admin_id');
            $table->string('subscription_id',500)->nullable();
            $table->string('plan_id',200)->nullable();
            $table->string('price_id',200)->nullable();
            $table->string('plan_staus',200)->nullable();
            $table->timestamp('plan_expiry')->nullable();
            $table->timestamp('grace_period_start')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
