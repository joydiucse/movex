<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBulkReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_returns', function (Blueprint $table) {
            $table->id();
            $table->string('batch_no', 191);
            $table->bigInteger('merchant_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('processed_by')->unsigned()->nullable();
            $table->bigInteger('delivery_man_id')->unsigned();
            $table->string('status', 50);
            $table->foreign('merchant_id')->references('id')->on('merchants')
            ->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('bulk_returns');
    }
}
