<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumsInCompaniesTabe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->Integer('screen_capture_duration')->default(0)->after('idle_default_approval');
            $table->string('screen_capture_image_size', 50)->nullable()->after('screen_capture_duration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $dropped_columns = ['screen_capture_duration','screen_capture_image_size'];
        foreach ($dropped_columns  as $column){
            if (Schema::hasColumn('companies', $column)) {
                Schema::table('companies', function (Blueprint $table) use($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
}
