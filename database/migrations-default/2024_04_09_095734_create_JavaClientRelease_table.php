<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('JavaClientRelease', function (Blueprint $table) {
            $table->increments('id');
            $table->string('OS',255);
            $table->string('architectutre', 255);
            $table->string('setup_path', 255);
            $table->string('version', 255);
            $table->string('check_sum_hash', 255);
            $table->string('active_status', 255);
            $table->Integer('created_by')->default(0);
            $table->string('created_date')->useCurrent();
            $table->timestamp('update_date')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('JavaClientRelease');
    }
};
