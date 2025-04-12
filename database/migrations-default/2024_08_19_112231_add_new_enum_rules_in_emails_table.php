<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
            $prefix = DB::getTablePrefix();

            DB::statement("ALTER TABLE ".$prefix."emails CHANGE COLUMN trial trial ENUM('start','complete_plan_active','in_progress','trial_expire','cancel','payment_declined','payment_post_grace_declined','trial_plan_successful_payment') DEFAULT NULL ");

            DB::statement("ALTER TABLE ".$prefix."emails CHANGE COLUMN active_plan active_plan ENUM('payment_declined','payment_successful','last_day_of_post_grace_period','cancel_subscription','payment_successfully_closure_plan') DEFAULT NULL ");

            DB::statement("ALTER TABLE ".$prefix."emails CHANGE COLUMN card card ENUM('close_to_expiry','expired','card_updation') DEFAULT NULL ");

            DB::statement("ALTER TABLE ".$prefix."emails CHANGE COLUMN cleanup cleanup ENUM('instance_cleanup','closure_plan') DEFAULT NULL ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            $prefix = DB::getTablePrefix();

            DB::statement("ALTER TABLE ".$prefix."emails CHANGE COLUMN trial trial ENUM('start','complete_plan_active','in_progress','trial_expire','cancel','payment_declined') DEFAULT NULL ");

            DB::statement("ALTER TABLE ".$prefix."emails CHANGE COLUMN active_plan active_plan ENUM('payment_declined','payment_successful','last_day_of_post_grace_period','cancel_subscription') DEFAULT NULL ");

            DB::statement("ALTER TABLE ".$prefix."emails CHANGE COLUMN cleanup cleanup ENUM('instance_cleanup') DEFAULT NULL ");

    }
};
