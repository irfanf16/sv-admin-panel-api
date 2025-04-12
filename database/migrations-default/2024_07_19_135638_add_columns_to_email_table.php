<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $fields = ['addons' => ['purchase_add_ons','expire_add_ons'],'bucket' => ['buy']];
        foreach ($fields as $fieldName => $field){
            if(!Schema::hasColumn('emails',$fieldName)){
                Schema::table('emails', function (Blueprint $table) use ($fieldName, $field){
                    $table->enum($fieldName,$field)->nullable();
                });
            }
        }
        $prefix = DB::getTablePrefix();
        DB::statement("ALTER TABLE ".$prefix."emails CHANGE COLUMN service service ENUM('trial','active_plan','card','cleanup','addons','bucket') NOT NULL ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $fields = ['addons','bucket' ];
        foreach ($fields as  $field){
            if(Schema::hasColumn('emails',$field)){
                Schema::table('emails', function (Blueprint $table) use ( $field){
                    $table->dropColumn($field);
                });
            }
        }
        $prefix = DB::getTablePrefix();
        DB::statement("ALTER TABLE ".$prefix."emails CHANGE COLUMN service service ENUM('trial','active_plan','card','cleanup') NOT NULL ");
    }
};
