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
        Schema::create('addons_histories', function (Blueprint $table) {
            // $table->uuid('id')->primary(); // Set UUID as the primary key
            $table->id();
            $table->integer('company_id');
            $table->integer('product_id');
            $table->string('subscription_id');
            $table->string('stripe_product_id');
            $table->string('stripe_price_id');
            $table->longText('current_plan_features')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->timestamp('remove_date')->nullable();
            $table->string('type')->nullable();
            $table->boolean('status')->default(1)->comment('1: Active, 0: Inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addons_histories');
    }
};
