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
        Schema::table('web_app_tracking', function (Blueprint $table) {
            $table->tinyInteger('os')->after('spend_time')->default(1)->comment('1 for window, 2 for Mac, 3 for linux');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_app_tracking', function (Blueprint $table) {
            $table->dropColumn('os');
        });
    }
};
