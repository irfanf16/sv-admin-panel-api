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
        Schema::create('emails', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('subject',500);
            $table->enum('service',['trial','active_plan','card','cleanup']);
            $table->enum('trial',['start','complete_plan_active','in_progress', 'trial_expire', 'cancel','payment_declined'])->nullable();
            $table->enum('active_plan',['payment_declined','payment_successful'])->nullable();
            $table->enum('card',['close_to_expiry','expired'])->nullable();
            $table->enum('cleanup',['instance_cleanup'])->nullable();
            $table->integer('days')->default(0);
            $table->tinyInteger('status')->comment('enable = 1, disable = 0')->default(0);
            $table->tinyInteger('occurrence')->comment('after = 1, before = 2')->nullable();
            $table->longText('email_body')->comment('html/text for email body ')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
