<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeChangesInCompaniesTimezoneAndWebAppTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('web_app_tracking', function (Blueprint $table) {
//            $table->string('description')->collation('utf8mb4_unicode_ci')->change();
//        });
        DB::select('ALTER TABLE web_app_tracking CHANGE description description TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL ');
        DB::select('ALTER TABLE projects CHANGE description description TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL ');
        DB::select('ALTER TABLE activities CHANGE description description TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL ');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('web_app_tracking', 'description')) {
                DB::select('ALTER TABLE web_app_tracking CHANGE description description TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL ');
        }
        if (Schema::hasColumn('projects', 'description')) {
                DB::select('ALTER TABLE projects CHANGE description description TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL ');
        }
        if (Schema::hasColumn('activities', 'description')) {
                DB::select('ALTER TABLE activities CHANGE description description TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL ');
        }

    }
}
