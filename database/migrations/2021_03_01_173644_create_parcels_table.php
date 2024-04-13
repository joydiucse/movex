<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcels', function (Blueprint $table) {
            $table->id();
            $table->string('parcel_no', 50)->index()->comment('used for each merchant invoice tracking');
            $table->string('tracking_number', 50)->index()->comment('used for paperfly parcel tracking');
            $table->string('short_url', 50)->index()->comment('short-link for tracking');

            // Start For charge

            $table->string('packaging')->default('no')->comment('1 yes, 0 no');
            $table->decimal('packaging_charge')->nullable()->comment('recorded for future reference');
            $table->boolean('fragile')->default(false)->comment('recorded for previous reference');
            $table->decimal('fragile_charge')->nullable()->comment('recorded for future reference');

            $table->string('parcel_type')->nullable()->comment('parcel type or parcel service');
            $table->string('weight')->nullable();
            $table->decimal('charge')->nullable()->comment('weight delivery charge from merchant table');
            $table->decimal('cod_charge')->nullable()->comment('COD charge direct from from merchant table');
            $table->decimal('vat')->nullable()->comment('custom er wise vat from merchant table');
            $table->string('location')->nullable()->comment('for future reference');

            // total
            $table->decimal('total_delivery_charge')->default(0.00)->nullable()->comment('vat + cod + delivery charge');
            $table->decimal('payable')->default(0.00)->nullable()->comment('total payable to Merchant');
            //if parcel get returned
            $table->decimal('return_charge')->default(0.00)->comment('parcel return charge if get returned');
            // End for charge

            $table->decimal('price')->default(0);
            $table->bigInteger('merchant_id')->unsigned();
            $table->bigInteger('delivery_man_id')->unsigned()->nullable();
            $table->bigInteger('pickup_man_id')->unsigned()->nullable();
            $table->bigInteger('return_delivery_man_id')->unsigned()->nullable();
            $table->bigInteger('transfer_delivery_man_id')->unsigned()->nullable();
            //added on 09-06
            $table->bigInteger('hub_id')->unsigned()->nullable();
            $table->bigInteger('pickup_hub_id')->unsigned()->nullable();
            $table->bigInteger('transfer_to_hub_id')->unsigned()->nullable();
            $table->bigInteger('third_party_id')->unsigned()->nullable();
            $table->bigInteger('withdraw_id')->unsigned()->nullable();

            //new added column
            $table->decimal('delivery_fee')->default(0.00);
            $table->decimal('pickup_fee')->default(0.00);
            $table->decimal('return_fee')->default(0.00);

            $table->integer('user_id')->unsigned()->comment('created by');
            $table->string('customer_name',100);
            $table->string('customer_invoice_no',50);
            $table->string('customer_phone_number',30);
            $table->text('customer_address');

            $table->date('pickup_date')->nullable();
            $table->time('pickup_time')->nullable();

            $table->date('delivery_date')->nullable();
            $table->time('delivery_time')->nullable();
            // new added
            $table->date('date')->nullable()->comment('date added to get specific date data on where clause');

            $table->string('pickup_shop_phone_number')->nullable();
            $table->text('pickup_address')->nullable();

            $table->string('note')->nullable();
            $table->enum('status', ['pending', 'deleted', 'pickup-assigned', 're-schedule-pickup', 'received-by-pickup-man', 'received','transferred-to-hub','transferred-received-by-hub', 'delivery-assigned', 're-schedule-delivery','returned-to-greenx','return-assigned-to-merchant','partially-delivered','delivered','delivered-and-verified', 'returned-to-merchant','cancel', 're-request'])->default('pending')->comment('current status of parcel, re-request for after cancel by staff the merchant can re request parcel.');
            $table->string('status_before_cancel')->nullable()->comment('status before cancel the parcel, it will use when re-request parcel');
            $table->boolean('is_partially_delivered')->default(false)->comment('true for partially delivered');
            $table->decimal('price_before_delivery')->default(0)->comment('parcel price before partially delivered');

            $table->boolean('is_paid')->default(false)->comment('true for payment completed');
            $table->integer('merchant_otp')->nullable();

            $table->integer('otp')->nullable()->comment('to verify parcel successfully delivered to customer');

            $table->foreign('merchant_id')->references('id')->on('merchants')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('delivery_man_id')->references('id')->on('delivery_men')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('pickup_man_id')->references('id')->on('delivery_men')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('transfer_delivery_man_id')->references('id')->on('delivery_men')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('return_delivery_man_id')->references('id')->on('delivery_men')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('hub_id')->references('id')->on('hubs')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('pickup_hub_id')->references('id')->on('hubs')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('transfer_to_hub_id')->references('id')->on('hubs')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('third_party_id')->references('id')->on('third_parties')
                ->onUpdate('cascade')->onDelete('set null');
            $table->foreign('withdraw_id')->references('id')->on('merchant_withdraws')
                ->onUpdate('cascade')->onDelete('set null');

            //from update
            $table->decimal('selling_price')->default(0)->comment('parcel actual price for damage of parcel money return purpose');

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
        Schema::dropIfExists('parcels');
    }
}
