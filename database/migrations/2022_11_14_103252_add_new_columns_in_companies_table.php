<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsInCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {

            if (!Schema::hasColumn('companies', 'date_format')) {
                $table->string('date_format',30)->after('theme_json');
            }
            if (!Schema::hasColumn('companies', 'weekdays')) {
                $table->string('weekdays',30)->default(0)->after('theme_json');
            }
            if (!Schema::hasColumn('companies', 'time_format')) {
                $table->integer('time_format')->default(12)->after('date_format');
            }
            if (!Schema::hasColumn('companies', 'emp_start_no')) {
                $table->integer('emp_start_no')->default(1)->after('time_format');
            }
            if (!Schema::hasColumn('companies', 'working_hours')) {
                $table->integer('working_hours')->default(1)->after('time_format');
            }
            if (!Schema::hasColumn('companies', 'emp_code_length')) {
                $table->integer('emp_code_length')->default(2)->after('emp_start_no');
            }
            if (!Schema::hasColumn('companies', 'currency_sign')) {
                $table->integer('currency_sign')->default(0)->after('currency_id');
            }



            $table->integer('idle_time_threshold')->default(10)->change();
            $table->integer('screen_capture_limit')->default(3)->change();
            $table->string('screen_capture_image_size')->default('low')->change();
            $table->integer('screen_capture_duration')->default(10)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('companies', 'idle_time_threshold')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->integer('idle_time_threshold')->default(0)->change();
            });
        }
        if (Schema::hasColumn('companies', 'screen_capture_limit')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->integer('screen_capture_limit')->default(0)->change();
            });
        }
        if (Schema::hasColumn('companies', 'screen_capture_image_size')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('screen_capture_image_size')->default('')->change();
            });
        }
        if (Schema::hasColumn('companies', 'screen_capture_duration')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('screen_capture_duration')->default(0)->change();
            });
        }

        $dropped_columns = ['date_format','time_format','emp_start_no','emp_code_length','currency_sign','working_hours','weekdays'];
        foreach ($dropped_columns  as $column){
            if (Schema::hasColumn('companies', $column)) {
                Schema::table('companies', function (Blueprint $table) use($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
}
