<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompletionTimeInArticleCompletion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('article_completion', 'completion_time')) {
            Schema::table('article_completion', function (Blueprint $table) {
                $table->time('completion_time')->after('completion')->default('00:00:00');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('article_completion', 'completion_time')) {
            Schema::table('article_completion', function (Blueprint $table) {
                $table->dropColumn('completion_time');
            });
        }
    }
}
