<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIncludeWeekendInTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('activities', 'include_weekend')) {
            Schema::table('activities', function (Blueprint $table) {
                $table->tinyInteger('include_weekend')->after('type')->default(0);
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
        if (Schema::hasColumn('activities', 'include_weekend')) {
            Schema::table('activities', function (Blueprint $table) {
                $table->dropColumn('include_weekend');
            });
        }
    }
}
