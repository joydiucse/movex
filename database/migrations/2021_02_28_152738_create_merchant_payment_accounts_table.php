<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantPaymentAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_payment_accounts', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('merchant_id')->unsigned();
            $table->foreign('merchant_id')->references('id')->on('merchants')->onUpdate('cascade')->onDelete('cascade');
            $table->string('selected_bank')->nullable()->comment('for bank account');
            $table->string('bank_branch')->nullable()->comment('selected bank branch name');
            $table->string('bank_ac_name')->nullable()->comment('bank account owner name');
            $table->string('bank_ac_number')->nullable()->comment('bank account number');
            $table->string('routing_no')->nullable()->comment('routing number');
            $table->string('bkash_number')->nullable()->comment('bkash account number');
            $table->string('bkash_ac_type')->nullable()->comment('bkash account type');
            $table->string('rocket_number')->nullable()->comment('rocket account number');
            $table->string('rocket_ac_type')->nullable()->comment('rocket account type');
            $table->string('nogod_number')->nullable()->comment('nogod account number');
            $table->string('nogod_ac_type')->nullable()->comment('nogod account type');
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
        Schema::dropIfExists('merchant_payment_accounts');
    }
}
