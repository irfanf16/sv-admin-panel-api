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
            $table->integer('user_time_sheet_limit')->default(90)->after('capture_screen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies_users', function (Blueprint $table) {
            $table->dropColumn('user_time_sheet_limit');
        });
    }
};
