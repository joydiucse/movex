<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_withdraws', function (Blueprint $table) {
            $table->id();
            $table->string('withdraw_id')->nullable()->comment('auto generated 10digit id');
            $table->bigInteger('merchant_id')->unsigned();
            $table->text('note')->nullable();
            $table->decimal('amount')->nullable();
            $table->string('status')->nullable()->default('pending')->comment('pending, approved, processed, rejected');
            $table->string('withdraw_to')->comment('in which account type gets withdraw');
            $table->text('account_details')->comment('account in which merchant withdraw his money');
            $table->integer('created_by')->unsigned()->nullable();
            $table->date('date')->nullable();

            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
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
        Schema::dropIfExists('merchant_withdraws');
    }
}
