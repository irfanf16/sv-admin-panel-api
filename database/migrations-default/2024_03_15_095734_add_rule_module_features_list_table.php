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
        $prefix = config('database.connections.'.config('database.default').'.prefix');
        $columns = \DB::select('describe '.$prefix.'module_features_list');
       
        $change_type = false;
        if(is_array($columns)) {
            foreach ($columns as $key => $column) {
                if($column->Field == 'feature_key' && str_contains($column->Type, 'int')) {
                    $change_type = true;
                    break;
                }
            }
        }
        
        if($change_type) {
            Schema::table('module_features_list', function (Blueprint $table) {
                $table->string('feature_key', 255)->change();
            });
        }

        if(!Schema::hasColumn('module_features_list', 'rule')) {
            Schema::table('module_features_list', function (Blueprint $table) {
                $table->string('rule', 255)->nullable()->after('type');
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
        if(Schema::hasColumn('module_features_list', 'rule')) {
            Schema::table('module_features_list', function (Blueprint $table) {
                $table->dropColumn('rule');
            });
        }
    }
};
