<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_transfers', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('from_account_id')->unsigned()->nullable();
            $table->foreign('from_account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('to_account_id')->unsigned()->nullable();
            $table->foreign('to_account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');

            $table->date('date')->nullable();
            $table->dateTime('date_time')->nullable();
            $table->text('note')->nullable();
            $table->decimal('amount')->default(0);

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
        Schema::dropIfExists('fund_transfers');
    }
}
