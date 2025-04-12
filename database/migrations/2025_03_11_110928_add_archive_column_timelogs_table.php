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
        Schema::table('timelogs', function (Blueprint $table) {
            if (!Schema::hasColumn('timelogs', 'is_archive')) {
                $table->tinyInteger('is_archived')
                    ->after('rate') // Adjust the position
                    ->default(1) // Set default value
                    ->comment('1 for enabled, 2 for archive');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timelogs', function (Blueprint $table) {
            if (Schema::hasColumn('timelogs', 'is_archived')) {
                $table->dropColumn('is_archived');
            }
        });
    }
};
