<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChatGroupMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_group_members', function(Blueprint $t){
            $t->increments('id');
            $t->integer('group_id')->length(10)->unsigned();
            $t->integer('user_id')->length(10)->unsigned();
            $t->timestamps();

            $t->foreign('user_id')->references('id')->on('users');
            $t->foreign('group_id')->references('id')->on('chat_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_group_members');
    }
}
