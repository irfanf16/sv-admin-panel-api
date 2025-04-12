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
        if(!Schema::hasColumn('companies_users', 'invited_by') && !Schema::hasColumn('companies_users','invitation_date')){
            Schema::table('companies_users', function (Blueprint $table) {
                $table->string('invited_by',50);
                $table->date('invitation_date')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasColumn('companies_users', 'invited_by') && Schema::hasColumn('companies_users','invitation_date')){
            Schema::table('companies_users', function (Blueprint $table) {
                $table->integer('invited_by');
                $table->date('invitation_date');
            });
        }
    }
};
