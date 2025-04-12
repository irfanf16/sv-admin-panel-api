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
        if(!Schema::hasColumn('timelogs', 'summary_log_status')) {
            Schema::table('timelogs', function (Blueprint $table) {
                $table->boolean('summary_log_status')->default(0)->after('deleted');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('timelogs', 'summary_log_status')) {
            Schema::table('timelogs', function (Blueprint $table) {
                $table->dropColumn('summary_log_status');
            });
        }
    }
};
