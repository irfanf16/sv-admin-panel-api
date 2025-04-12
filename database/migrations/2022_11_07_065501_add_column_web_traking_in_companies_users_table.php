<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnWebTrakingInCompaniesUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies_users', function (Blueprint $table) {
                $table->tinyInteger('web_tracking')->default(1)->after('allow_tracking');
                $table->tinyInteger('idle_time_tracking')->default(1)->after('web_tracking');
                $table->string('default_approval',40)->nullable()->after('idle_time_tracking');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $dropped_columns = ['web_tracking','idle_time_tracking','default_approval'];
        foreach ($dropped_columns  as $column){
            if (Schema::hasColumn('companies_users', $column)) {
                Schema::table('companies_users', function (Blueprint $table) use($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
    
}
