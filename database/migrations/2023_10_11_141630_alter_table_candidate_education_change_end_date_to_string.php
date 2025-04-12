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
        if(Schema::hasColumn('candidate_education', 'end_date') && Schema::hasColumn('candidate_education','percentage')){
            Schema::table('candidate_education', function (Blueprint $table) {
                $table->string('end_date', 5)->change();
                $table->string('percentage', 11)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasColumn('candidate_education', 'end_date') && Schema::hasColumn('candidate_education','percentage')){
            Schema::table('candidate_education', function (Blueprint $table) {
                $table->date('end_date')->change();
                $table->float('percentage')->change();
            });
        }
    }
};
