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
        if(!Schema::hasColumn('companies_users', 'blocked_by_super_admin')) {
            Schema::table('companies_users', function (Blueprint $table) {
                $table->tinyInteger('blocked_by_super_admin')->default(0);
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
        if(Schema::hasColumn('companies_users', 'blocked_by_super_admin')) {
            Schema::table('companies_users', function (Blueprint $table) {
                $table->dropColumn('blocked_by_super_admin');
            });
        }
    }
};
