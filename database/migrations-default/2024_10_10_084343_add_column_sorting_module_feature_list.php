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
        Schema::table('module_features_list', function (Blueprint $table) {
            $table->integer('sort_by')->nullable(); // Add sort_by column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('module_features_list', function (Blueprint $table) {
            $table->dropColumn('sort_by'); // Remove category_id column
        });
    }
};
