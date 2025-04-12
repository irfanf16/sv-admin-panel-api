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
        Schema::table('article_completion', function (Blueprint $table) {
            if (!Schema::hasColumn('article_completion', 'submitted')) {
                $table->tinyInteger('submitted')
                    ->default(0)
                    ->comment('0 for not submitted,1 for submitted, 2 for timeover');
            }
        });
        Schema::table('quiz_attempts_status', function (Blueprint $table) {
            if (!Schema::hasColumn('quiz_attempts_status', 'submitted')) {
                $table->tinyInteger('submitted')
                    ->default(0)
                    ->comment('0 for not submitted,1 for submitted, 2 for timeover');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('article_completion', function (Blueprint $table) {
            if (Schema::hasColumn('article_completion', 'submitted')) {
                $table->dropColumn('submitted');
            }
        });
        Schema::table('quiz_attempts_status', function (Blueprint $table) {
            if (Schema::hasColumn('quiz_attempts_status', 'submitted')) {
                $table->dropColumn('submitted');
            }
        });
    }
};
