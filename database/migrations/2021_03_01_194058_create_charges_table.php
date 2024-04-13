<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charges', function (Blueprint $table) {
            $table->id();
            $table->string('weight')->nullable();
            $table->decimal('same_day')->default(0.00);
            $table->decimal('next_day')->default(0.00);
            // $table->decimal('frozen')->default(0.00);
            $table->decimal('sub_city')->default(0.00);
            $table->decimal('outside_dhaka')->default(0.00);
            // $table->decimal('third_party_booking')->default(0.00);
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
        Schema::dropIfExists('charges');
    }
}
