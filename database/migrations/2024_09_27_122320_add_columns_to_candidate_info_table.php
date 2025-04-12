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
        Schema::table('candidate_info', function (Blueprint $table) {
            $table->string('total_experience')->nullable()->after('exit_date');
            $table->string('relevant_experience')->nullable()->after('total_experience');
            $table->string('primary_contact', 255)->change();
            $table->string('secondary_contact', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_info', function (Blueprint $table) {
            $table->dropColumn('total_experience');
            $table->dropColumn('relevant_experience');
        });
    }
};
