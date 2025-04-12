<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveChatForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('direct_messages', function(Blueprint $table){
            
            $table->dropForeign('fuser');
            $table->dropForeign('tuser');
            $table->dropForeign('pmsg');
            
        });

        Schema::table('group_messages', function(Blueprint $table){
            
            $table->dropForeign('gfuser');
            $table->dropForeign('gid');
            $table->dropForeign('gpmsg');

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

            $table->tinyInteger('has_reply')->default(0)->after('thread_count');
            $table->integer('parent_msg')->length(10)->unsigned()->nullable()->change();

            $table->foreign('from_user', 'fuser')->references('id')->on('users');
            $table->foreign('to_user', 'tuser')->references('id')->on('users');
            $table->foreign('parent_msg', 'pmsg')->references('id')->on('direct_messages');

        });

        Schema::table('group_messages', function(Blueprint $table){

            $table->tinyInteger('has_reply')->default(0)->after('thread_count');
            $table->integer('parent_msg')->length(10)->unsigned()->nullable()->change();

            $table->foreign('from_user', 'gfuser')->references('id')->on('users');
            $table->foreign('group_id', 'gid')->references('id')->on('groups');
            $table->foreign('parent_msg', 'gpmsg')->references('id')->on('group_messages');

        });
    }
}
