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
        
        \DB::select('SET FOREIGN_KEY_CHECKS=0;');

        $prefix = config('database.connections.'.config('database.default').'.prefix');
        $columns = \DB::select('describe '.$prefix.'companies');
       
        $change_type = false;
        if(is_array($columns)) {
            foreach ($columns as $key => $column) {
                if($column->Field == 'plan_id' && str_contains($column->Type, 'int')) {
                    $change_type = true;
                    break;
                }
            }
        }
        
        if($change_type) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('plan_id', 255)->change();
            });
        }
        if(!Schema::hasColumn('companies', 'plan_id')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('plan_id', 255)->after('updated_at');
            });
        }
        if(!Schema::hasColumn('companies', 'subscription_id')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('subscription_id', 255)->after('updated_at');
            });
        }
        if(!Schema::hasColumn('companies', 'plan_expiry')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->timestamp('plan_expiry')->nullable()->after('updated_at');
            });
        }
        if(!Schema::hasColumn('companies', 'plan_staus')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('plan_staus', 255)->nullable()->after('updated_at');
            });
        }
        // if(!Schema::hasColumn('companies', 'price_id')) {
        //     Schema::table('companies', function (Blueprint $table) {
        //         $table->string('price_id', 255)->after('updated_at');
        //     });
        // }
        \DB::select('SET FOREIGN_KEY_CHECKS=1;');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasColumn('companies', 'plan_id')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('plan_id');
            });
        }
        if(Schema::hasColumn('companies', 'subscription_id')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('subscription_id');
            });
        }
        if(Schema::hasColumn('companies', 'plan_expiry')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('plan_expiry');
            });
        }
        if(Schema::hasColumn('companies', 'plan_staus')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('plan_staus');
            });
        }
        // if(Schema::hasColumn('companies', 'price_id')) {
        //     Schema::table('companies', function (Blueprint $table) {
        //         $table->dropColumn('price_id');
        //     });
        // }
        
    }
};
