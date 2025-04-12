<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('companies', 'payment_status')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('payment_status', 155)->nullable();
            });
        }
        if (!Schema::hasColumn('companies', 'grace_period')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->integer('grace_period')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('companies', 'grace_period')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('grace_period');
            });
        }

        if (Schema::hasColumn('companies', 'payment_status')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('payment_status');
            });
        }
    }
};
