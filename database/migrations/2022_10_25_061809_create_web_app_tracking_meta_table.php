<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebAppTrackingMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_app_tracking_meta', function (Blueprint $table) {
            $table->id();
            $table->string('app', 150)->nullable()->comment('Save application name (Like chrome.exe)');
            $table->text('description')->nullable()->comment('Save application description');
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
        Schema::dropIfExists('web_app_tracking_meta');
    }
}
