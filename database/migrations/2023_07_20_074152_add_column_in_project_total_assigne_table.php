<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInProjectTotalAssigneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->integer('members_count')->default(0);
        });
        Schema::table('activities', function (Blueprint $table) {
            $table->integer('members_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('members_count')->default(0);
        });
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('members_count')->default(0);
        });
    }
}
