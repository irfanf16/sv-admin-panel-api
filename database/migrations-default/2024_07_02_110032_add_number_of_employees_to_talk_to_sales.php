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

        if(!Schema::hasColumn('talk_to_sales', 'number_of_employees')) {
            Schema::table('talk_to_sales', function (Blueprint $table) {
                $table->string('number_of_employees')->nullable()->after('message');
                $table->string('status')->default(1)->after('number_of_employees');
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasColumn('talk_to_sales', 'number_of_employees')) {
            Schema::table('talk_to_sales', function (Blueprint $table) {
                $table->dropColumn('number_of_employees');
                $table->dropColumn('status');
            });
        }
    }
};
