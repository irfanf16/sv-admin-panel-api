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
        Schema::create('web_app_tracking_summariez', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('user_id')->length(11)->default(0);
            $table->tinyInteger('type')->default(1);
            $table->string('app_name',170)->nullable();
            $table->date('date_entry')->nullable();
            $table->string('spent_time',170)->nullable();
            $table->integer('url_count')->length(11)->default(0);
            $table->string('domain_name',170)->nullable();
            $table->text('full_url')->nullable();
            $table->string('timezone',50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_app_tracking_summariez');
    }
};
