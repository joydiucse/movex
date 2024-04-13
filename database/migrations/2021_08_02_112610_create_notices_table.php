<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('details');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->boolean('status')->default(true)->comment('0 inactive, 1 active');
            $table->boolean('staff')->default(false)->comment('0 inactive, 1 active');
            $table->boolean('merchant')->default(false)->comment('0 inactive, 1 active');
            $table->string('alert_class')->default('alert-warning');
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
        Schema::dropIfExists('notices');
    }
}
