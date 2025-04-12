<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChatContentType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_messages', function(Blueprint $table){
            $table->longText('content')->change();
        });

        Schema::table('direct_messages', function(Blueprint $table){
            $table->longText('content')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_messages', function(Blueprint $table){
            $table->string('content', 256)->change();
        });

        Schema::table('direct_messages', function(Blueprint $table){
            $table->string('content', 256)->change();
        });
    }
}
