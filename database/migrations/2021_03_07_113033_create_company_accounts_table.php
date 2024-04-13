<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // company account means income/expense
        Schema::create('company_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('source')->nullable();

            $table->text('details')->nullable();
            $table->date('date')->nullable();
            $table->dateTime('date_time')->nullable();

            $table->string('type')->nullable()->comment('income/credit, expense/debit');

            $table->decimal('amount')->nullable()->comment('positive and negative amount define by type');
            $table->decimal('balance')->nullable()->comment('grand balance');

            // account expense add

            $table->string('create_type')->nullable()->comment('user_defined for admin create');

            //from update
            $table->text('receipt')->nullable()->comment('any receipt uploaded');
            $table->text('reject_reason')->nullable()->comment('if request rejected');
            $table->text('transaction_id')->nullable()->comment('if request processed');

            //from update_v0

            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');

            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('merchant_id')->unsigned()->nullable();
            $table->foreign('merchant_id')->references('id')->on('merchants')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('parcel_id')->unsigned()->nullable();
            $table->foreign('parcel_id')->references('id')->on('parcels')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('merchant_withdraw_id')->unsigned()->nullable();
            $table->foreign('merchant_withdraw_id')->references('id')->on('merchant_withdraws')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('delivery_man_id')->unsigned()->nullable();
            $table->foreign('delivery_man_id')->references('id')->on('delivery_men')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('account_id')->unsigned()->nullable()->comment('bank method/account wise cash colection');
            $table->foreign('account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('company_accounts');
    }
}
