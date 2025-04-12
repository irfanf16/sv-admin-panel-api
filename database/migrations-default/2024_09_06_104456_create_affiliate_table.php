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
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->string('tenantId');
            $table->string('type')->nullable();
            $table->string('publicId');
            $table->dateTime('registeredAt');
            $table->string('email')->nullable();
            $table->string('name')->nullable();
            $table->string('tierId')->nullable();
            $table->string('tierName')->nullable();
            $table->string('accountId')->nullable();
            $table->string('sourceId')->nullable();
            $table->string('sourceStatus')->nullable();
            $table->text('fraudSuspicion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_close_account');
    }
};
