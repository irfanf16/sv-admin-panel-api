<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChatGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_groups', function(Blueprint $t){
            $t->increments('id');
            $t->string('group_name', 50);
            $t->integer('group_lead')->nullable()->length(10)->unsigned();
            $t->integer('status')->default(1);
            $t->integer('created_by')->length(10)->unsigned();
            $t->integer('company_id')->length(10)->unsigned();
            $t->timestamps();

            $t->foreign('group_lead')->references('id')->on('users');
            $t->foreign('created_by')->references('id')->on('users');
            $t->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_groups');
    }
}
