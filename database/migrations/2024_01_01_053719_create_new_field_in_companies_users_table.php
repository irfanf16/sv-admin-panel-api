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
        Schema::table('companies_users', function (Blueprint $table) {
            $table->integer('idle_time_threshold')->default(10)->after('idle_time_tracking');
            $table->string('idle_time_inactive',10)->default('None')->after('idle_time_tracking');
            $table->integer('screen_capture_limit')->default(3)->after('capture_screen');
            $table->integer('screen_capture_duration')->default(10)->after('capture_screen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies_users', function (Blueprint $table) {
            $table->dropColumn('idle_time_threshold');
            $table->dropColumn('idle_time_inactive');
            $table->dropColumn('screen_capture_limit');
            $table->dropColumn('screen_capture_duration');
        });
    }
};
