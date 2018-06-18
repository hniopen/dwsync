<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHascMadaTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hasc_mada', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code_region', 20)->nullable();
            $table->string('region', 160)->nullable();
            $table->string('code_district', 20)->nullable();
            $table->string('district', 160)->nullable();
            $table->string('code_commune', 20)->nullable();
            $table->string('commune', 160)->nullable();
            $table->string('code_fkt', 20)->nullable();
            $table->string('fkt', 160)->nullable();
            $table->string('code_village', 20)->nullable();
            $table->string('village', 160)->nullable();
            $table->string('csb',160)->nullable();
            $table->string('epp',160)->nullable();
            $table->string('college',160)->nullable();
            $table->string('lycee',160)->nullable();
            $table->string('date_submission',40)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hasc_mada');
    }

}
