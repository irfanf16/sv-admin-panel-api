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
            if (!Schema::hasColumn('companies', 'idle_time_limit')) {
                Schema::table('companies', function (Blueprint $table) {
                    $table->integer('idle_time_limit')->default(5)->after('emp_code_length');
                });
            }
            if (!Schema::hasColumn('companies', 'local_data_storage_limit')) {
                Schema::table('companies', function (Blueprint $table) {
                    $table->time('local_data_storage_limit')->nullable()->comment('If value = Null then value is equal to infinity')->default("05:00:00")->after('idle_time_limit');
                });
            }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            if (Schema::hasColumn('companies', 'idle_time_limit')) {
                Schema::table('companies', function (Blueprint $table) {
                    $table->dropColumn('idle_time_limit');
                });
            }
            if (Schema::hasColumn('companies', 'local_data_storage_limit')) {
                Schema::table('companies', function (Blueprint $table) {
                    $table->dropColumn('local_data_storage_limit');
                });
            }
    }
};
