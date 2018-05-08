<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriberStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriber_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('date');
            $table->integer('phones');
            $table->integer('phones_verified');
            $table->integer('emails');
            $table->integer('emails_verified');
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
        Schema::dropIfExists('subscriber_statistics');
    }
}
