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
            $table->string('employment_type')->nullable()->after('bill');
            $table->string('salary_type')->nullable()->after('employment_type');
            $table->string('salary')->nullable()->after('salary_type');
            $table->string('billable_tracking')->nullable()->after('salary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies_users', function (Blueprint $table) {
            $table->dropColumn('employment_type');
            $table->dropColumn('salary_type');
            $table->dropColumn('salary');
            $table->dropColumn('billable_tracking');
        });
    }
};
