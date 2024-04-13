<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('shops', function (Blueprint $table) {
//            $table->string('contact_number')->nullable()->after('shop_phone_number');
//        });
        Schema::table('parcels', function (Blueprint $table) {
            $table->string('tracking_number', 50)->index()->comment('used for paperfly parcel tracking')->after('parcel_no');
            $table->string('short_url', 50)->index()->comment('short-link for tracking')->after('tracking_number');
//            $table->bigInteger('shop_id')->unsigned()->nullable()->after('pickup_address');
//            $table->foreign('shop_id')->references('id')->on('shops')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('shops', function (Blueprint $table) {
//            $table->dropColumn('contact_number');
//        });
        Schema::table('parcels', function (Blueprint $table) {
            $table->dropColumn('tracking_number');
            $table->dropColumn('short_url');

            $table->dropForeign('parcels_shop_id_foreign');
            $table->dropColumn('shop_id');
        });
    }
}
