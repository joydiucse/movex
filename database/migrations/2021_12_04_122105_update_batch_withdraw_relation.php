<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBatchWithdrawRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_withdraws', function (Blueprint $table) {
            $table->bigInteger('withdraw_batch_id')->unsigned()->nullable()->after('created_by');
            $table->foreign('withdraw_batch_id')->references('id')->on('withdraw_batches')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_withdraws', function (Blueprint $table) {
            $table->dropForeign('withdraw_batch_id_foreign');
            $table->dropColumn('withdraw_batch_id');
        });
    }
}
