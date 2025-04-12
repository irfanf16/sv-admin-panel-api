<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuthKeyInPushNotifiction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('push_notifications', 'auth_key')){
            Schema::table('push_notifications', function (Blueprint $table) {
                $table->string('auth_key')->nullable()->after('user_id');
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
        if(Schema::hasColumn('push_notifications', 'auth_key')){
            Schema::table('push_notifications', function (Blueprint $table) {
                $table->dropColumn('auth_key');
            });
        }
    }
}
