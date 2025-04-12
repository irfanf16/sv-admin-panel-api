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
        Schema::create('deletion_stats', function (Blueprint $table) {
            $table->id();
            $table->string('company_id', 255);
            $table->string('data_source', 50);
            $table->string('resource_name', 255)->nullable();
            $table->bigInteger('data_size_bytes');
            $table->bigInteger('records_deleted');
            $table->dateTime('deletion_timestamp');
            $table->string('deletion_status', 50);
            $table->text('error_message')->nullable();
            $table->bigInteger('retention_policy_id')->nullable();
            $table->text('extra_info')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deletion_stats');
    }
};
