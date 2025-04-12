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
        \DB::select('SET FOREIGN_KEY_CHECKS=0;');
        if(!Schema::hasColumn('modules', 'rules'))
        {

            Schema::table('modules', function (Blueprint $table) {
                $table->json('rules')->nullable();
            });

        }
        \DB::select('SET FOREIGN_KEY_CHECKS=1;');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasColumn('modules', 'rules')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->dropColumn('rules');
            });
        }
    }
};
