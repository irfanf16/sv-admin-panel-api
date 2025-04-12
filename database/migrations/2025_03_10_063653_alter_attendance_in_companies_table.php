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
        Schema::table('companies', function (Blueprint $table) {
            // Add the column if it doesn't exist
            if (!Schema::hasColumn('companies', 'is_attendance_enabled')) {
                $table->tinyInteger('is_attendance_enabled')
                    ->after('enable_web_app_tracking')
                    ->default(2) // Set default value
                    ->comment('1 for enabled, 2 for disabled');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Drop the column if it exists
            if (Schema::hasColumn('companies', 'is_attendance_enabled')) {
                $table->dropColumn('is_attendance_enabled');
            }
        });
    }
};
