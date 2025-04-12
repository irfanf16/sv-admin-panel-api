<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Groups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('groups')) {
            Schema::create('groups', function(Blueprint $t){
                $t->increments('id');
                $t->string('group_name', 50);
                $t->integer('group_lead')->length(10)->unsigned();
                $t->integer('department_id')->length(10)->unsigned()->nullable();
                $t->integer('status')->default(1);
                $t->integer('created_by')->length(10)->unsigned();
                $t->timestamps();

                $t->foreign('group_lead')->references('id')->on('users');
                $t->foreign('department_id')->references('id')->on('departments');
                $t->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('groups');
    }
}
