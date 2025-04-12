<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ParentMsgDefault0 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('direct_messages', function(Blueprint $table){
            $table->integer('parent_msg')->length(10)->default(0)->change();
        });

        Schema::table('group_messages', function(Blueprint $table){
            $table->integer('parent_msg')->length(10)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('direct_messages', function(Blueprint $table){
            $table->integer('parent_msg')->length(10)->nullable()->change();
        });

        Schema::table('group_messages', function(Blueprint $table){
            $table->integer('parent_msg')->length(10)->nullable()->change();
        });
    }
}
