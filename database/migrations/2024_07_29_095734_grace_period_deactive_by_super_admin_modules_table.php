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
        if(!Schema::hasColumn('modules', 'deactive_by_super_admin')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->tinyInteger('deactive_by_super_admin')->default(0);
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
        if(Schema::hasColumn('modules', 'deactive_by_super_admin')) {
            Schema::table('modules', function (Blueprint $table) {
                $table->dropColumn('deactive_by_super_admin');
            });
        }
    }
};
