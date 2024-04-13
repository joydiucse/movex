<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hubs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->comment('in charge user');
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('phone_number')->nullable();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('hub_id')->unsigned()->nullable();
            $table->foreign('hub_id')->references('id')->on('hubs')
                ->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_hub_id_foreign');
            $table->dropColumn('hub_id');
        });
        Schema::dropIfExists('hubs');

    }
}
