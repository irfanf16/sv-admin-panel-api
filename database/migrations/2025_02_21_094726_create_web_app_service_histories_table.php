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
        Schema::create('web_app_service_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Use unsignedBigInteger for foreign keys
            $table->tinyInteger('action_type')->comment('1 for start, 2 for stop'); // Use tinyInteger for small values
            $table->timestamp('action_date_time')->nullable(); // Correct definition of a timestamp column
            $table->timestamps(); // This automatically creates `created_at` and `updated_at`
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_app_service_histories');
    }
};
