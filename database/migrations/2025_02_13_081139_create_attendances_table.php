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
        Schema::create('attendances', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID as Primary Key
            $table->unsignedInteger('user_id');
            $table->date('attendance_date');
            $table->timestamp('clock_in')->nullable();
            $table->timestamp('clock_out')->nullable();
            $table->timestamp('user_entered_at')->nullable();
            $table->timestamp('deleted_at')->nullable(); // Set to nullable for soft deletes
            $table->json('type')->nullable();
            $table->timestamp('created_at')->useCurrent(); // Auto-set current timestamp
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate(); // Auto-update on change
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
