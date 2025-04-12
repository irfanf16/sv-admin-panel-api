<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('address',100)->nullable()->after('logo');
            $table->string('contact_no',20)->nullable()->after('address');

            $table->integer('industry_id')->nullable()->after('contact_no');
            $table->integer('currency_id')->nullable()->after('industry_id');
            $table->integer('start_weekday')->nullable()->after('currency_id');
            $table->integer('timezone_id')->nullable()->after('start_weekday');

            $table->string('theme',20)->nullable()->after('timezone_id');

            $table->tinyInteger('enable_mfa')->default(1)->comment('1 for disable, 2 for enable')->after('theme');
            $table->tinyInteger('enable_time_tracking')->default(1)->comment('1 for disable, 2 for enable')->after('enable_mfa');
            $table->tinyInteger('enable_time_capture')->default(1)->comment('1 for disable, 2 for enable')->after('enable_time_tracking');

            $table->integer('enable_screen_capture')->default(1)->comment('1 for disable, 2 for enable')->after('enable_time_capture');
            $table->integer('screen_capture_limit')->default(0)->after('enable_screen_capture');

            $table->tinyInteger('enable_web_app_tracking')->default(1)->comment('1 for disable, 2 for enable')->after('screen_capture_limit');
            $table->tinyInteger('enable_idle_time')->default(1)->comment('1 for disable, 2 for enable')->after('enable_web_app_tracking');
            $table->integer('idle_time_threshold')->default(0)->after('enable_idle_time');
            $table->string('idle_default_approval',40)->nullable()->after('idle_time_threshold');
            $table->text('theme_json')->nullable()->after('idle_default_approval');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $dropped_columns = ['address', 'contact_no','industry_id','currency_id','start_weekday','timezone_id','theme','enable_mfa','enable_time_tracking',
        'enable_time_capture','enable_screen_capture','screen_capture_limit','enable_web_app_tracking','enable_idle_time','idle_time_threshold',
            'idle_default_approval','theme_json'];
        foreach ($dropped_columns  as $column){
            if (Schema::hasColumn('companies', $column)) {
                Schema::table('companies', function (Blueprint $table) use($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
}
