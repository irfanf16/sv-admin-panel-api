<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_master', function (Blueprint $table) {
            $table->increments('im_id');
            $table->integer('super_admin_id')->length(11);
            $table->float('total_payments');
            $table->integer('currency_id')->length(11);
            $table->dateTime('invoice_date');
            $table->dateTime('expiry_date');
            $table->string('file_path')->nullable();
            $table->string('status')->comment('paid\nunpaid	');
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
        Schema::dropIfExists('invoice_master');
    }
}
