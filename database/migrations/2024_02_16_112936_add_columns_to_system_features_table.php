<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('system_features', function (Blueprint $table) {
            $table->tinyInteger('status')->default('2')->comment('1=active, 2=in-active')->after('pf_id');
            $table->string('feature_value', 255)->after('pf_id');
            $table->string('feature_key',255)->after('pf_id');
            $table->integer('sub_module_id')->after('pf_id');
            $table->integer('parent_module_id')->after('pf_id');
            $table->integer('package_id')->after('pf_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $drop_columns = ['package_id', 'parent_module_id', 'sub_module_id', 'feature_key', 'feature_value', 'status'];
        foreach ($drop_columns as $key => $column) {
            Schema::table('system_features', function (Blueprint $table) use($column) {
                if (Schema::hasColumn('system_features', $column))
                {
                    Schema::table('system_features', function (Blueprint $table) use($column)
                    {
                        $table->dropColumn($column);
                    });
                }
            });
        }
        
    }
};
