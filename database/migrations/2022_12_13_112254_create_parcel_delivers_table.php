<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcelDeliversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcel_delivers', function (Blueprint $table) {
            $table->id();
            $table->string('parcel_no', 50)->index()->comment('used for each merchant invoice tracking');
            $table->bigInteger('delivery_man_id')->unsigned();
            $table->integer('user_id')->unsigned()->comment('created by');
            $table->date('assign_date')->nullable();
            $table->enum('status', ['delivery-assigned',  're-schedule-delivery'])->comment('Percel delivery Status.');
            $table->string('batch_no', 191);
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
        Schema::dropIfExists('parcel_delivers');
    }
}
