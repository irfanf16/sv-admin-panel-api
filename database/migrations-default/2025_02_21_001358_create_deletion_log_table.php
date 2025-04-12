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
        Schema::create('deletion_log', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('company_db',50)->nullable();
            $table->tinyInteger('service_type')->default(1);
            $table->text('command_error')->nullable();
            $table->timestamp('service_run_time')->useCurrent();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deletion_log');
    }
};
