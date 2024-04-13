<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcelReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcel_returns', function (Blueprint $table) {
            $table->id();
            $table->string('parcel_no', 50)->index()->comment('used for each merchant invoice tracking');
            $table->bigInteger('merchant_id')->unsigned();
            $table->bigInteger('return_man_id')->unsigned();
            $table->integer('user_id')->unsigned()->comment('created by');
            $table->date('return_date')->nullable();
            $table->enum('status', ['pending',  'return-assigned-to-merchant', 'returned-to-merchant', 'reversed'])->default('pending')->comment('Percel Return Status.');
            $table->string('batch_no', 191);
            $table->foreign('parcel_no')->references('parcel_no')->on('parcels')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('merchant_id')->references('id')->on('merchants')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('return_man_id')->references('id')->on('delivery_men')
            ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::dropIfExists('parcel_returns');
    }
}
