<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('description',300)->nullable();
            $table->tinyInteger('active')->length(4)->nullable();
            $table->tinyInteger('billable')->length(1);
            $table->tinyInteger('type')->length(1);
            $table->integer('budget')->length(11)->nullable()->default(0);
            $table->integer('sync_frequency')->length(11)->nullable();
            $table->integer('company_id')->length(10)->unsigned();
            $table->tinyInteger('is_deleted')->length(1)->default(0);
            // $table->tinyInteger('allow_tracking')->length(4)->nullable();
            $table->integer('created_by')->length(10)->unsigned();
            $table->integer('updated_by')->length(10)->unsigned();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
