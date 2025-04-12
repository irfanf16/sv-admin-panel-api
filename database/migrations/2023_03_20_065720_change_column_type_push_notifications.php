<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnTypePushNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('push_notifications', 'message')){
            Schema::table('push_notifications', function (Blueprint $table) {
                $table->text('message')->change();
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
        if(Schema::hasColumn('push_notifications', 'message')){
            Schema::table('push_notifications', function (Blueprint $table) {
                $table->string('message')->nullable()->change();
            });
        }
    }
}
