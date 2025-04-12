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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_attendance_enabled')) {
                $table->tinyInteger('is_attendance_enabled')
                    ->after('productivity')
                    ->default(2)
                    ->comment('1 for enabled, 2 for disabled');
            }

            $table->string('attendance_relaxation_time')
                ->after('is_attendance_enabled')
                ->default('00:00:00');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_attendance_enabled');
            $table->dropColumn('attendance_relaxation_time');
        });
    }
};
