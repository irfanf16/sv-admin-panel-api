<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAndAddNewColumnsInWebAppTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('web_app_tracking', 'file_type') && Schema::hasColumn('web_app_tracking','file_type') && Schema::hasColumn('web_app_tracking','website_url'))
        {
            Schema::table('web_app_tracking', function (Blueprint $table) {
                $table->dropColumn(['file_name', 'file_type', 'website_url']);
            });
        }


        Schema::table('web_app_tracking', function (Blueprint $table) {
            $table->text('app')->comment(' to store app name (e.g WINDWARD.EXE)')->after('type');
            $table->text('description')->comment('text to store window title (e.g. Mobile API for settings and MO.docx - Protected View - Word)')->after('app');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $dropped_columns = ['app', 'description'];
        foreach ($dropped_columns  as $column){
            if (Schema::hasColumn('web_app_tracking', $column)) {
                Schema::table('web_app_tracking', function (Blueprint $table) use($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
}
