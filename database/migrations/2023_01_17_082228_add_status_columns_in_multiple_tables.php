<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusColumnsInMultipleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        $hasForeignKey = DB::select(DB::raw("
//                SELECT constraint_name FROM information_schema.key_column_usage
//                WHERE table_name = 'user_breaks' AND column_name = 'scheduled_by'
//                AND constraint_name <> 'FOREIGN KEY'"));
//        if (count($hasForeignKey) > 0) {
//            \DB::statement('ALTER TABLE users ADD FULLTEXT search(first_name, last_name, email)');
//        }



        if(!Schema::hasColumn('shift_breaks', 'status')){
            Schema::table('shift_breaks', function (Blueprint $table) {
                $table->tinyInteger('status')->default(1)->comment('1 = enable, 2 =  disabled');
            });
        }

        if(!Schema::hasColumn('shifts_users', 'status')){
            Schema::table('shifts_users', function (Blueprint $table) {
                $table->tinyInteger('status')->default(1)->comment('1 = enable, 2 =  disabled');
            });
        }
        if(!Schema::hasColumn('user_breaks', 'status')){
            Schema::table('user_breaks', function (Blueprint $table) {
                $table->tinyInteger('status')->default(1)->comment('1 = enable, 2 =  disabled');
            });
        }

        if(!Schema::hasColumn('timelogs', 'break_id')){
            Schema::table('timelogs', function (Blueprint $table) {
                $table->integer('break_id')->nullable()->after('type');
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
        $tables = ['shift_breaks','shifts_users', 'user_breaks'];

        foreach ($tables  as $table){
            if (Schema::hasColumn($table, 'status')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('status');
                });
            }
        }

        if (Schema::hasColumn('timelogs', 'break_id')) {
            Schema::table('timelogs', function (Blueprint $table) {
                $table->dropColumn('break_id');
            });
        }

    }
}
