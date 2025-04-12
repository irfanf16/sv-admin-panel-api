<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebAppTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_app_tracking', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('task_id')->nullable();
            $table->tinyInteger('task_status')->nullable()->comment('task status according to user');
            $table->tinyInteger('type')->default(1)->comment('1 for Website , 2 for Apps');
            $table->string('file_type',50)->nullable()->comment('add file type ( like .exe, .jpg )');
            $table->text('file_name')->nullable()->comment('add complete Filename / Webpage');
            $table->text('website_url')->nullable()->comment('add complete website url');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();

            // $table->foreign('company_id')->references('id')->on('companies');
            // $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('task_id')->references('id')->on('activities');
            // $table->fulltext(['file_name','website_url']);

        });
        DB::statement('ALTER TABLE web_app_tracking ADD FULLTEXT search(file_name, website_url)');

        
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_app_tracking');
    }
}
