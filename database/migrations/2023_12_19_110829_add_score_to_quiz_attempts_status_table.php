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
        Schema::table('quiz_attempts_status', function (Blueprint $table) {
            $table->string('obtained_score')->default(0)->after('quiz_completion');
            $table->string('total_score')->default(0)->after('obtained_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_attempts_status', function (Blueprint $table) {
            $table->dropColumn('obtained_score');
            $table->dropColumn('total_score');
        });
    }
};
