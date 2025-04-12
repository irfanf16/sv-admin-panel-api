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
            $table->string('domain_label')->nullable()->after('domain_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_app_tracking_summariez', function (Blueprint $table) {
            $table->dropColumn('domain_label');
        });
    }
};
