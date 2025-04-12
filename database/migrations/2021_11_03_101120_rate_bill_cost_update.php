<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RateBillCostUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timelogs', function (Blueprint $table) {
            $table->decimal('rate', 15, 4)->default(0)->after('type');
            $table->decimal('bill', 15, 4)->default(0)->after('type');
            $table->decimal('cost', 15, 4)->default(0)->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('timelogs', function (Blueprint $table) {
            $table->dropColumn('rate');
            $table->dropColumn('bill');
            $table->dropColumn('cost');
        });
    }
}
