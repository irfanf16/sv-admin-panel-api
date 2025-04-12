<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBreakTypeInUserBreaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_breaks', function (Blueprint $table) {
            $table->tinyInteger('break_type')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('user_breaks','break_type')){
            Schema::table('user_breaks', function (Blueprint $table) {
                $table->dropColumn('break_type');
            });
        }

    }
}
