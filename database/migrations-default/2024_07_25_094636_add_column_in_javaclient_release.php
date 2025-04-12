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
        if(!Schema::hasColumn('JavaClientRelease','is_full_installer')){
            Schema::table('JavaClientRelease', function (Blueprint $table) {
                $table->smallInteger('is_full_installer')->default(1)->after('created_by')->comment('1 = full Installer , 0 = update Installer');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasColumn('JavaClientRelease','is_full_installer')){
            Schema::table('JavaClientRelease', function (Blueprint $table) {
                $table->dropColumn('is_full_installer');
            });
        }
    }
};
