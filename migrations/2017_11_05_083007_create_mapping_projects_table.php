<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMappingProjectsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mapping_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project1')->nullable();
            $table->integer('project2')->nullable();
            $table->string('dateLastExported')->nullable();
            $table->tinyInteger('isActive')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mapping_projects');
    }
}
