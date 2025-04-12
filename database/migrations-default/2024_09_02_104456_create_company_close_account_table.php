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
        Schema::create('company_close_account', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('plan_type')->nullable();
            $table->string('reason');
            $table->text('message')->nullable();
            $table->dateTime('closing_time');
            $table->softDeletes();
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
