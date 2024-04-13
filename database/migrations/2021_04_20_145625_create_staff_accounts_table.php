<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_accounts', function (Blueprint $table) {
            $table->id();

            $table->string('source')->nullable()->comment('i will use it for make description more meaningful');

            $table->text('details')->nullable();
            $table->date('date')->nullable();
            $table->dateTime('date_time')->nullable();

            $table->string('type')->nullable()->comment('income/credit, expense/debit');

            $table->decimal('amount')->nullable()->comment('positive and negative amount define by type');
            $table->decimal('balance')->nullable()->comment('grand balance');

            $table->integer('user_id')->unsigned()->nullable()->comment('staff id for easy get');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('account_id')->unsigned()->nullable();
            $table->foreign('account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('company_account_id')->unsigned()->nullable();
            $table->foreign('company_account_id')->references('id')->on('company_accounts')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('fund_transfer_id')->unsigned()->nullable();
            $table->foreign('fund_transfer_id')->references('id')->on('fund_transfers')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('from_account_id')->unsigned()->nullable();
            $table->foreign('from_account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('to_account_id')->unsigned()->nullable();
            $table->foreign('to_account_id')->references('id')->on('accounts')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('staff_accounts');
    }
}
