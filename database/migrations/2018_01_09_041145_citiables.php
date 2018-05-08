<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Citiables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('citiables', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('city_id')->unsigned();
		    $table->integer('citiable_id')->unsigned();
		    $table->string('citiable_type');
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
	    Schema::dropIfExists('citiables');
    }
}
