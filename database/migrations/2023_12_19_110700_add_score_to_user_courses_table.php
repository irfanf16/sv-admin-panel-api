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
        Schema::table('user_courses', function (Blueprint $table) {
            $table->string('total_time')->default('00:00:00')->after('course_completion');
            $table->string('obtained_score')->default(0)->after('total_time');
            $table->string('total_score')->default(0)->after('obtained_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_courses', function (Blueprint $table) {
            $table->dropColumn('total_time');
            $table->dropColumn('obtained_score');
            $table->dropColumn('total_score');
        });
    }
};
