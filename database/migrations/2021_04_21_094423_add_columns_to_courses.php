<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function(Blueprint $table){
            $table->integer('is_published')->default(0)->after('company_id');
            $table->integer('req_completion')->default(0)->after('is_published');
            $table->integer('force_order')->default(0)->after('req_completion');
            $table->integer('is_library')->default(0)->after('force_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function(Blueprint $table){
            $table->dropColumn(['is_published', 'req_completion', 'force_order', 'is_library']);
        });
    }
}
