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
        if(!Schema::hasColumn('companies_users', 'manual_time_entry_approval')) {
            Schema::table('companies_users', function (Blueprint $table) {
                $table->text('manual_time_entry_approval')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies_users', function (Blueprint $table) {
            $table->dropColumn(['manual_time_entry_approval']);
        });
    }
};
