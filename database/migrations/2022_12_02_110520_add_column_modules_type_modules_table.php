<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnModulesTypeModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modules', function (Blueprint $table) {
                $table->tinyInteger('module_type')->default(1)->after('parent_module_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $dropped_columns = ['module_type'];
        foreach ($dropped_columns  as $column){
            if (Schema::hasColumn('modules', $column)) {
                Schema::table('modules', function (Blueprint $table) use($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
}
