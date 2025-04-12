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
        Schema::create('web_app_tracking_domain_users', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->integer('domain_id')->default(0);
            $table->tinyInteger('productivity')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_app_tracking_domain_users');
    }
};
