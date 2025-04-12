<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GroupMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('group_members')) {
            Schema::create('group_members', function(Blueprint $t){
                $t->increments('id');
                $t->integer('user_id')->length(10)->unsigned();
                $t->integer('group_id')->length(10)->unsigned();
                $t->timestamps();

                $t->foreign('user_id')->references('id')->on('users');
                $t->foreign('group_id')->references('id')->on('groups');
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
        Schema::dropIfExists('group_members');
    }
}
