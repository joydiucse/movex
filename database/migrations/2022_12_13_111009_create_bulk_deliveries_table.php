<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBulkDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('batch_no', 191);
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('processed_by')->unsigned()->nullable();
            $table->bigInteger('delivery_man_id')->unsigned();
            $table->date('assign_date');
            $table->string('status', 50);
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
        Schema::dropIfExists('bulk_deliveries');
    }
}
