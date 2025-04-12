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
        if(!Schema::hasColumn('companies','has_setup'))
        {
            Schema::table('companies', function (Blueprint $table) {
                $table->boolean('has_setup')->default(0)->comment('Once user setup the company it will become 1');
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('has_setup');
        });
    }
};
