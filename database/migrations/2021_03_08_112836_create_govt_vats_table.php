<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGovtVatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('govt_vats', function (Blueprint $table) {
            $table->id();
            $table->string('source')->nullable()->comment('i will use it for make description more meaningful');

            
            $table->date('date')->nullable();
            $table->text('details')->nullable();
            
            $table->string('type')->nullable()->comment('income/credit, expense/debit');

            $table->decimal('amount')->nullable();

            $table->bigInteger('parcel_id')->unsigned()->nullable();
            $table->foreign('parcel_id')->references('id')->on('parcels')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('govt_vats');
    }
}
