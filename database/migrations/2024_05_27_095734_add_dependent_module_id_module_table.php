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
        if(!Schema::hasColumn('modules', 'dependent_module_id')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->integer('dependent_module_id')->default(0);
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
        if(Schema::hasColumn('modules', 'dependent_module_id')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->dropColumn('dependent_module_id');
            });
        }
    }
};
