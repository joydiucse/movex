<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryMenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_men', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 30);
            $table->string('city', 100)->nullable();
            $table->string('zip', 15)->nullable()->comment('postal code');
//            $table->string('state', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('status', 30)->default('active')->index();
            $table->string('driving_license')->nullable();
            $table->decimal('pick_up_fee')->default(0.00)->comment()->nullable();
            $table->decimal('delivery_fee')->default(0.00)->comment()->nullable();
            $table->decimal('return_fee')->default(0.00)->comment()->nullable();
            $table->timestamps();
            $table->string('sip_extension', 151)->nullable();
            $table->string('sip_password', 151)->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_men');
    }
}
