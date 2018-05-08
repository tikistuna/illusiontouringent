<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttributesForAutomaticTextingRemindersToEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->tinyInteger('two_week_reminder_sent')->default(0);
            $table->tinyInteger('six_week_reminder_sent')->default(0);
            $table->text('reminder_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('two_week_reminder_sent');
            $table->dropColumn('six_week_reminder_sent');
            $table->dropColumn('reminder_description');
        });
    }
}
