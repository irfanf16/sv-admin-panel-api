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
        Schema::create('web_app_tracking_meta', function (Blueprint $table) {
            $table->id();
            $table->text('app')->nullable();
            $table->text('app_url')->nullable();
            $table->text('description')->nullable();
            $table->string('app_logo',170)->nullable();
            $table->string('app_extension',10)->nullable();
            $table->tinyInteger('type')->default(1);
            $table->tinyInteger('productivity')->default(1);
            $table->timestamps();
            $table->unique('app');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_app_tracking_meta', function (Blueprint $table) {
            $table->dropUnique(['app']);  // Drop the unique constraint
        });
        Schema::dropIfExists('web_app_tracking_meta');
    }
};
