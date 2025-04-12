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
        
        
        if(Schema::hasColumn('categories', 'price_type')) {
            Schema::table('categories', function (Blueprint $table) {
                //$table->enum('price_type', ['per_unit', 'one_time', 'tiers'])->default('per_unit')->comment('per_unit,one_time,tiers')->change();
				DB::statement("ALTER TABLE typicms_categories MODIFY COLUMN price_type enum('per_unit', 'one_time', 'tiers') DEFAULT 'per_unit'");
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

    }
};
