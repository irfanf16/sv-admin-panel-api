<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityUserLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_user_logs', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->nullable();
            $table->integer('module_id')->nullable();
            $table->integer('user_id')->nullable()->comment('current login user id');
            $table->ipAddress('ip')->nullable();
            $table->string('system_info',200)->nullable();
            $table->string('browser_info',200)->nullable();
            $table->string('action_type',100)->nullable();
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
        Schema::dropIfExists('activity_user_logs');
    }
}
