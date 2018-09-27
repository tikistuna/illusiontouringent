<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveEmailSubscriberStatistics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriber_statistics', function (Blueprint $table) {
            $table->dropColumn('emails');
            $table->dropColumn('emails_verified');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriber_statistics', function (Blueprint $table) {
	        $table->integer('emails');
	        $table->integer('emails_verified');
        });
    }
}
