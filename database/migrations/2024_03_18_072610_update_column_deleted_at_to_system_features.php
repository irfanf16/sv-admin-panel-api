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
    
        if(Schema::hasColumn('system_features', 'deleted_at')) {
            Schema::table('system_features', function (Blueprint $table) {
                $table->timestamp('deleted_at')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
 
        
    }
};
