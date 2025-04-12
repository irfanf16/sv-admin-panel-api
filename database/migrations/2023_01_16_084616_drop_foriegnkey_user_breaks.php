<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class DropForiegnkeyUserBreaks extends Migration
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
            // Schema::table('user_breaks', function ($table) {
            //     $table->dropForeign('user_breaks_scheduled_by_foreign');
            // });
       // }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
