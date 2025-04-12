<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChatMsgs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direct_messages', function(Blueprint $table){
            $table->increments('id');
            $table->string('content');
            $table->integer('from_user')->unsigned()->length(10)->nullable();
            $table->integer('to_user')->unsigned()->length(10)->nullable();
            $table->integer('parent_msg')->unsigned()->length(10)->nullable();
            //$table->json('delivery_status')->nullable();
           // $table->json('delivery_status')->nullable()->collation('utf8mb4_unicode_ci');
            $table->json('delivery_status')->nullable();
            $table->json('revisions')->nullable();
            $table->integer('thread_count')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('direct_messages');
    }
}
