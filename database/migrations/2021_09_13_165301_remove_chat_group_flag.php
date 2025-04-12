<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveChatGroupFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('groups', 'is_chat_group')) {

            Schema::table('groups', function(Blueprint $table){
                $table->dropColumn('is_chat_group');
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
        Schema::table('groups', function(Blueprint $table){
            $table->integer('is_chat_group')->default(0);
        });
    }
}
