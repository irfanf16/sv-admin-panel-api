<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChatDataTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_messages', function(Blueprint $table){
            $table->longText('delivery_status')->nullable()->change();
            $table->longText('revisions')->nullable()->change();
        });

        Schema::table('direct_messages', function(Blueprint $table){
            $table->longText('delivery_status')->nullable()->change();
            $table->longText('revisions')->nullable()->change();
        });
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
