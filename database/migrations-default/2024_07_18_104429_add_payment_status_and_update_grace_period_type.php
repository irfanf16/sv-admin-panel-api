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
        if (!Schema::hasColumn('companies', 'payment_status')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->tinyInteger('payment_status')->nullable();
            });
        }
//        if (Schema::hasColumn('companies', 'grace_period')) {
//            Schema::table('companies', function (Blueprint $table) {
//                $table->integer('grace_period')->change();
//            });
//        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('companies', 'payment_status')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('payment_status');
            });
        }
    }
};
