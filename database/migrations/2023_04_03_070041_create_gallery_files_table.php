<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleryFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_files', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('folder_id')->nullable()->unsigned();
            $table->enum('type',['a','v','d','i','o','f'])->nullable();
            $table->string('name')->nullable();
            $table->string('path')->nullable();
            $table->string('extension',8)->nullable();
            $table->string('mimetype',100)->nullable();
            $table->integer('width')->length(10)->unsigned()->nullable();
            $table->integer('height')->length(10)->unsigned()->nullable();
            $table->integer('filesize')->length(10)->unsigned()->nullable();

            $table->longText('title')->nullable();
            $table->longText('description')->nullable();
            $table->longText('alt_attribute')->nullable();

            $table->tinyInteger('focal_x')->length(3)->nullable()->unsigned();
            $table->tinyInteger('focal_y')->length(3)->nullable()->unsigned();
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
        Schema::dropIfExists('gallery_files');
    }
}
