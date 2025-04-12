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
        Schema::create('web_app_tracking_domain', function (Blueprint $table) {
            $table->id();
            $table->text('domain_name')->nullable();
            $table->text('domain_url')->nullable();
            $table->text('app_logo')->nullable();
            $table->string('app_logo_ext',10)->nullable();
            $table->tinyInteger('type')->default(1);
            $table->tinyInteger('productivity')->default(1);
            $table->tinyInteger('domain_validation')->default(1);
            $table->timestamps();
            // $table->unique('domain_url', 'web_app_tracking_domain_domain_url_unique', 2048);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_app_tracking_domain', function (Blueprint $table) {
            //$table->dropUnique(['domain_url']);  // Drop the unique constraint
        });
        Schema::dropIfExists('web_app_tracking_domain');
    }
};
