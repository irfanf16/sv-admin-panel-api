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
        Schema::table('web_app_tracking_meta', function (Blueprint $table) {
            $table->string('app_logo',170)->after('description')->nullable();
            $table->string('app_extension',170)->after('app_logo')->nullable();
            $table->tinyInteger('type')->after('app_extension')->default(2);
            $table->tinyInteger('productivity')->after('type')->default(2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_app_tracking_meta', function (Blueprint $table) {
            $table->dropColumn('app_logo');
            $table->dropColumn('app_extension');
            $table->dropColumn('type');
            $table->dropColumn('productivity');
        });
    }
};
