<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniquenessConstraintToCitiablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('citiables', function (Blueprint $table) {
            $table->unique(array('city_id', 'citiable_id', 'citiable_type'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('citiables', function (Blueprint $table) {
            $table->dropUnique('citiables_city_id_citiable_id_citiable_type_unique');
        });
    }
}
