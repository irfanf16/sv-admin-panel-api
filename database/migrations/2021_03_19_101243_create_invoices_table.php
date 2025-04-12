<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('i_id');
            $table->integer('invoice_master_id')->length(11);
            $table->integer('super_admin_id')->length(11);
            $table->integer('plan_id')->length(11);
            $table->integer('company_id')->length(11);
            $table->integer('currency_id')->length(11);
            $table->float('payment');
            $table->timestamp('deleted_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
