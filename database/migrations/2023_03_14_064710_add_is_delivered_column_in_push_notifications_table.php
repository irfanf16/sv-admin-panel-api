<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDeliveredColumnInPushNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            if(!Schema::hasColumn('push_notifications', 'is_delivered')){
                Schema::table('push_notifications', function (Blueprint $table) {
                    $table->tinyInteger('is_delivered')->after('read_status')->default(0)->comment('0 =  not delivered');
                });
            }
            if(!Schema::hasColumn('candidate_info', 'cnic_picture')){
                Schema::table('candidate_info', function (Blueprint $table) {
                    $table->string('cnic_picture')->nullable()->after('cnic');
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
        if(Schema::hasColumn('push_notifications', 'is_delivered')){
            Schema::table('push_notifications', function (Blueprint $table) {
                $table->dropColumn('is_delivered');
            });
        }
        if(Schema::hasColumn('candidate_info', 'cnic_picture')){
            Schema::table('candidate_info', function (Blueprint $table) {
                $table->dropColumn('cnic_picture');
            });
        }
    }
}
