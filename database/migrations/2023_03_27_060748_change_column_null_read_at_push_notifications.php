<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnNullReadAtPushNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('push_notifications', 'read_date')){
            Schema::table('push_notifications', function (Blueprint $table) {
                $table->dateTime('read_date')->nullable()->change();
            });
        }
        if(!Schema::hasColumn('push_notifications', 'deleted_at')){
            Schema::table('push_notifications', function (Blueprint $table) {
                $table->softDeletes();
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
        if(Schema::hasColumn('push_notifications', 'read_date')){
            Schema::table('push_notifications', function (Blueprint $table) {
                $table->dateTime('read_date')->change();
            });
        }
        if(Schema::hasColumn('push_notifications', 'deleted_at')){
            Schema::table('push_notifications', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
}
