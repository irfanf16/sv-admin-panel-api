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
            $table->integer('logout_status')->default(1); // Add sort_by column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies_users', function (Blueprint $table) {
            $table->dropColumn('logout_status'); // Remove category_id column
        });
    }
};
