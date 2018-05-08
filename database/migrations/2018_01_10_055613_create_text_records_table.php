<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('text_records', function (Blueprint $table) {
        	$table->increments('id');
			$table->bigInteger('text_record_id')->unsigned()->nullable();
			$table->smallInteger('code')->unsigned();
			$table->string('status');
			$table->smallInteger('credits')->unsigned()->nullable();
			$table->string('phone_number')->nullable();
			$table->integer('phone_id')->unsigned()->nullable();
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
        Schema::dropIfExists('text_records');
    }
}
