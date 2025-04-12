<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInTimelogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timelogs', function (Blueprint $table) {
            $table->string('default_idle_approval',10)->nullable()->comment('Save User Default settings')->after('type');
            $table->string('admin_idle_approval',10)->nullable()->comment('Save admin settings against idle time')->after('default_idle_approval');
            $table->integer('shift_id')->nullable()->after('admin_idle_approval');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $dropped_columns = ['default_idle_approval','admin_idle_approval', 'shift_id'];

        foreach ($dropped_columns  as $column){
            if (Schema::hasColumn('timelogs', $column)) {
                Schema::table('timelogs', function (Blueprint $table) use($column) {
                    $table->dropColumn($column);
                });
            }
        }

    }
}
