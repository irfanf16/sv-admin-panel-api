<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid',200)->nullable();
            $table->string('file_name',200)->nullable();
            $table->string('file_type',45)->nullable();
            $table->string('file_size',45)->nullable();
            $table->string('path',255)->nullable();
            $table->string('status',45)->nullable();
            $table->string('ref_type',45)->nullable();
            $table->bigInteger('to_user')->nullable();
            $table->bigInteger('from_user')->nullable();
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
        Schema::dropIfExists('files');
    }
}
