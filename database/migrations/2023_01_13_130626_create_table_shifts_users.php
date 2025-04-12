<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableShiftsUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('shifts_users')) {
            Schema::create('shifts_users', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->comment('Save User list');
                $table->integer('shift_id')->comment('Save Shift id');
                $table->timestamps();
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
        Schema::dropIfExists('table_shifts_users');
    }
}
