<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('timelog_summaries', function (Blueprint $table) {

            $table->id();
            $table->date('date');
            $table->integer('user_id');
            $table->bigInteger('project_id')->nullable();
            $table->bigInteger('activity_id')->nullable();
            $table->bigInteger('course_id')->nullable();
            $table->bigInteger('user_courses_id')->nullable();
            $table->bigInteger('active_seconds')->nullable();
            $table->bigInteger('idle_seconds')->nullable();
            $table->bigInteger('break_seconds')->nullable();
            $table->bigInteger('manual_seconds')->nullable();
            $table->double('activity_level')->default(0);
            $table->double('cost')->default(0);
            $table->double('bill')->default(0);
            $table->index(['project_id','date','user_id'],'idx_date_user_id');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timelog_summaries');
    }
};
