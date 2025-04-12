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
        Schema::create('companies_configuration', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->default(0);
            $table->integer('trial_data_deletion_days')->default(0);
            $table->integer('has_setup_deletion_days')->default(0);
            $table->integer('timelogs_deletion_days')->default(0);
            $table->integer('snapshot_deletion_days')->default(0);
            $table->integer('closeaccount_timelogs_deletion_days')->default(0);
            $table->integer('closeaccount_snapshot_deletion_days')->default(0);
            $table->integer('active_free_plan_days')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies_configuration');
    }
};
