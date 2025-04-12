<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeleteToTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('activities','deleted_at') ==  false)
        {
            Schema::table('activities', function (Blueprint $table) {
                $table->softDeletes()->after('bill');
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
        if(Schema::hasColumn('activities','deleted_at')) {
            Schema::table('activities', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
}
