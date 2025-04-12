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
        Schema::create('chat_bot_client', function (Blueprint $table) {
            $table->id();
            $table->string('client_email')->nullable();
            $table->string('client_name')->nullable();
            $table->string('client_phone')->nullable();
            $table->text('client_comments')->nullable();
            $table->tinyInteger('rating_stars')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_bot_client');
    }
};
