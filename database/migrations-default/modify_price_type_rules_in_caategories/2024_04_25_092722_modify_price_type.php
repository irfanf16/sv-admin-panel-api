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
        Schema::table('categories', function (Blueprint $table) {
            //$table->enum('price_type', ['per_unit', 'one_time', 'tiers', 'one_time_all_users'])->default('per_unit')->comment('per_unit,one_time,tiers,one_time_all_users')->change();
			DB::statement("ALTER TABLE typicms_categories MODIFY COLUMN price_type enum('per_unit', 'one_time', 'tiers', 'one_time_all_users') DEFAULT 'per_unit'");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->enum('price_type', ['per_unit', 'one_time', 'tiers'])->default('per_unit')->comment('per_unit,one_time,tiers')->change();
        });
    }
};
