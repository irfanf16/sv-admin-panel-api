<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusColumnsTotables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('breaks', 'status') === false && Schema::hasColumn('breaks', 'break_type') === false){
            Schema::table('breaks', function (Blueprint $table) {
                $table->tinyInteger('status')->default(1)->comment('1 = enable, 2 =  disabled');
                $table->tinyInteger('break_type')->default(1)->comment('1 = enable, 2 =  disabled');
            });
        }

        if(!Schema::hasColumn('break_slots', 'status')){
            Schema::table('break_slots', function (Blueprint $table) {
                $table->tinyInteger('status')->default(1)->comment('1 = enable, 2 =  disabled');
            });
        }
        if(!Schema::hasColumn('break_users', 'status')){
            Schema::table('break_users', function (Blueprint $table) {
                $table->tinyInteger('status')->default(1)->comment('1 = enable, 2 =  disabled');
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
        $tables = ['breaks','break_slots', 'break_users'];

        foreach ($tables  as $table){
            if (Schema::hasColumn($table, 'status')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('status');
                });
            }
        }
    }
}
