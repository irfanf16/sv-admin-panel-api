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
        // Change the column type from enum to text using raw SQL
        \DB::statement("ALTER TABLE ".$prefix."emails MODIFY trial TEXT DEFAULT NULL");
        \DB::statement("ALTER TABLE ".$prefix."emails MODIFY active_plan TEXT DEFAULT NULL");
        \DB::statement("ALTER TABLE ".$prefix."emails MODIFY card TEXT DEFAULT NULL");
        \DB::statement("ALTER TABLE ".$prefix."emails MODIFY cleanup TEXT DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $prefix = \DB::getTablePrefix();
        // Change the column type from enum to text using raw SQL
        \DB::statement("ALTER TABLE ".$prefix."emails MODIFY trial TEXT DEFAULT NULL");
        \DB::statement("ALTER TABLE ".$prefix."emails MODIFY active_plan TEXT DEFAULT NULL");
        \DB::statement("ALTER TABLE ".$prefix."emails MODIFY card TEXT DEFAULT NULL");
        \DB::statement("ALTER TABLE ".$prefix."emails MODIFY cleanup TEXT DEFAULT NULL");
    }
};
