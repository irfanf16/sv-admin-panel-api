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
        if (!Schema::hasColumn('candidate_info', 'country_flag')) {
            Schema::table('candidate_info', function (Blueprint $table) {
                $table->string('country_flag', 20)->nullable()->after('country');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('candidate_info', 'country_flag')) {
            Schema::table('candidate_info', function (Blueprint $table) {
                $table->dropColumn('country_flag');
            });
        }
    }
};
