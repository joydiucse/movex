<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryManAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_man_accounts', function (Blueprint $table) {
            $table->id();
            
            $table->string('source')->nullable();

            $table->text('details')->nullable();
            $table->date('date')->nullable();

            $table->string('type')->nullable()->comment('income/credit, expense/debit');

            $table->decimal('amount')->nullable()->comment('positive and negative amount define by type');
            $table->decimal('balance')->nullable()->comment('grand balance');

            $table->bigInteger('parcel_id')->unsigned()->nullable();
            $table->foreign('parcel_id')->references('id')->on('parcels')->onUpdate('cascade')->onDelete('cascade');
            
            $table->bigInteger('delivery_man_id')->unsigned()->nullable();
            $table->foreign('delivery_man_id')->references('id')->on('delivery_men')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('company_account_id')->unsigned()->nullable();
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
        Schema::dropIfExists('delivery_man_accounts');
    }
}
