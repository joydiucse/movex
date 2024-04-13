<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->string('company');
            $table->string('api_key')->nullable();
            $table->string('secret_key')->nullable();
            $table->string('vat',50)->nullable();
            $table->string('phone_number', 30);
            $table->string('city', 100)->nullable();
            $table->string('zip', 15)->nullable()->comment('postal code');
            $table->text('address')->nullable();
            $table->string('website')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0 inactive, 1 active');
            $table->string('billing_street')->nullable();
            $table->string('billing_city', 100)->nullable();
            $table->string('billing_zip', 15)->nullable()->comment('billing postal code');
            $table->boolean('registration_confirmed')->default(true)->comment('true confirmed, false not confirmed');
            $table->string('trade_license')->nullable();
            $table->string('nid')->nullable();
            $table->integer('key_account_id')->nullable();
            $table->integer('sales_agent_id')->nullable();
            $table->timestamps();

            $table->longText('charges')->nullable();
            $table->longText('cod_charges')->nullable();


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
        Schema::dropIfExists('merchants');
    }
}
