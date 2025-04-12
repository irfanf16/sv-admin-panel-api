<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable()->comment('set user id');
            $table->string('message')->nullable();
            $table->tinyInteger('message_type')->default(1)->comment('1 = Low, 2 = high, 3 = high with stop TTh');
            $table->tinyInteger('view_option')->default(1)->comment('1 = hide refresh on page, 2 = hide onclick, 3 = hide on due date');
            $table->dateTime('view_hide_date')->nullable()->comment('1 = hide refresh on page, 2 = hide onclick, 3 = hide on due date');
            $table->tinyInteger('view_profile')->default(1)->comment('1 = all, 2 = owner, 3 = admin, 4 = manager, 5 = user');
            $table->tinyInteger('read_status')->default(1)->comment('1 = new,2 = read');
            $table->tinyInteger('source_type')->default(1)->comment('1 = internal, 2 = master');
            $table->tinyInteger('target_app')->default(1)->comment('1 = All, 2 = web, 3 = desktop');
            $table->dateTime('read_date');
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
        Schema::dropIfExists('push_notifications');
    }
}
