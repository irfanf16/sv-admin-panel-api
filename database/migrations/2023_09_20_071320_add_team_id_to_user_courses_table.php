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
        Schema::table('user_courses', function (Blueprint $table) {
            $table->string('profile_id')->after('course_id')->default(null);
            $table->string('user_type')->after('profile_id')->default(null);
            $table->string('team_id')->after('user_type')->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_courses', function (Blueprint $table) {
            $table->dropColumn('profile_id');
            $table->dropColumn('user_type');
            $table->dropColumn('team_id');
        });
    }
};
