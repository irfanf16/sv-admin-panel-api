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
        Schema::table('web_app_tracking_summariez', function (Blueprint $table) {
            $table->tinyInteger('productivity')->after('timezone')->default(1);
            $table->tinyInteger('domain_validation')->after('timezone')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_app_tracking_summariez', function (Blueprint $table) {
            $table->dropColumn('productivity');
            $table->dropColumn('domain_validation');
        });
    }
};
