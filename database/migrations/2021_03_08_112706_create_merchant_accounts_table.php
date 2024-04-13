<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('source')->nullable();

            $table->bigInteger('merchant_withdraw_id')->unsigned()->nullable();
            $table->bigInteger('payment_withdraw_id')->unsigned()->nullable()->comment('withdraw_id on which this amount got calculated');
            $table->bigInteger('parcel_withdraw_id')->unsigned()->nullable()->comment('withdraw_id upon which this parcel got reverse');
            $table->boolean('is_paid')->default(false)->comment('true for payment completed for old cash receive only');
            $table->text('details')->nullable();
            $table->date('date')->nullable();
            $table->string('type')->nullable()->comment('income/credit, expense/debit');
            $table->decimal('amount')->nullable()->comment('positive and negative amount define by type');
            $table->decimal('balance')->nullable()->comment('grand balance');
            $table->bigInteger('merchant_id')->unsigned()->nullable();
            $table->bigInteger('parcel_id')->unsigned()->nullable();
            $table->bigInteger('company_account_id')->unsigned()->nullable();

            $table->foreign('merchant_withdraw_id')->references('id')->on('merchant_withdraws')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('payment_withdraw_id')->references('id')->on('merchant_withdraws')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('parcel_withdraw_id')->references('id')->on('merchant_withdraws')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('merchant_id')->references('id')->on('merchants')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('parcel_id')->references('id')->on('parcels')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('company_account_id')->references('id')->on('company_accounts')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('merchant_accounts');
    }
}
