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
        Schema::table('live_dashboard_login', function (Blueprint $table) {
            if (Schema::hasColumn('live_dashboard_login', 'company_id')) {
                $table->dropForeign(['company_id']);
            }
            if (Schema::hasColumn('live_dashboard_login', 'user_id')) {
                $table->dropForeign(['user_id']);
            }
        });

        // Now you can safely drop and recreate the table
        Schema::dropIfExists('live_dashboard_login');

        // Recreate the table
        Schema::create('live_dashboard_login', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->timestamp('login_time')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->tinyInteger('login_status')->default(2);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_dashboard_login');
    }
};
