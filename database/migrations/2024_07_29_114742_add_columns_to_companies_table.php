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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('plan_old_status', 50)->nullable();
            $table->timestamp('grace_period_start_date')->nullable();
            $table->text('company_admin_emails')->nullable();
            $table->tinyInteger('data_deletion')->default(1)->comment('1 for keep data, 2 for delete data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['plan_old_status', 'grace_period_start_date', 'company_admin_emails', 'data_deletion']);
        });
    }
};
