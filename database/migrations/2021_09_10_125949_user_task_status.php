<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserTaskStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_activities', function(Blueprint $table){
            $table->tinyInteger('activity_status')->after('created_by')->default(0)->comment('not started(0), in progress(1), completed(2)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_activities', function(Blueprint $table){
            $table->dropColumn('activity_status');
        });
    }
}
