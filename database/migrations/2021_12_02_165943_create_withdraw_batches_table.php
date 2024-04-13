<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_batches', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->nullable()->comment('created by');
            $table->bigInteger('account_id')->unsigned()->nullable()->comment('processed from account');
            $table->enum('batch_type',['bank','bkash','nogod','rocket'])->nullable();
            $table->string('batch_no');
            $table->string('title');
            $table->mediumText('note')->nullable();
            $table->enum('status',['pending','processed'])->default('pending');
            $table->text('receipt')->nullable()->comment('any receipt uploaded');

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('account_id')->references('id')->on('accounts')
                ->onUpdate('cascade')->onDelete('set null');
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
        Schema::dropIfExists('withdraw_batches');
    }
}
