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
        \DB::select('SET FOREIGN_KEY_CHECKS=0;');
        if(!Schema::hasColumn('modules', 'status')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->tinyInteger('status')->default(0)->after('rules');
            });
        }
        \DB::select('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('modules', 'status')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
