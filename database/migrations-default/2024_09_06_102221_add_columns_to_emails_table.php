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
        $prefix = \DB::getTablePrefix();
        \DB::statement("ALTER TABLE ".$prefix."emails MODIFY service TEXT");
        Schema::table('emails', function (Blueprint $table) {
            $table->text('subscription')->after('cleanup')->nullable();
            $table->text('invite_user')->after('subscription')->nullable();
            $table->text('forgot_password')->after('invite_user')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $prefix = \DB::getTablePrefix();
        \DB::statement("ALTER TABLE ".$prefix."emails MODIFY service TEXT");
        Schema::table('emails', function (Blueprint $table) {
            $table->dropColumn('subscription','invite_user', 'forgot_password');
        });
    }
};
