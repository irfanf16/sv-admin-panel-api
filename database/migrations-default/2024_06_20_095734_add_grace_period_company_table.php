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
        if(!Schema::hasColumn('companies', 'grace_period')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->timestamp('grace_period')->nullable();
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
        if(Schema::hasColumn('companies', 'grace_period')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('grace_period');
            });
        }
    }
};
