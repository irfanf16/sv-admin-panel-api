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
        
        $prefix = config('database.connections.'.config('database.default').'.prefix');
        $columns = \DB::select('describe '.$prefix.'system_features');
        $change_type = false;
        if(is_array($columns)) {
            foreach ($columns as $key => $column) {
                if($column->Field == 'package_id' && str_contains($column->Type, 'int')) {
                    $change_type = true;
                    break;
                }
            }
        }
        if($change_type) {
            Schema::table('system_features', function (Blueprint $table) {
                $table->string('package_id', 255)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
