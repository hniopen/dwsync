<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMappingQuestionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mapping_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mappingProjectId')->nullable();
            $table->integer('question1')->nullable();
            $table->integer('question2')->nullable();
            $table->string('functions', 128)->nullable();
            $table->string('arg1', 512)->nullable();
            $table->string('arg2', 512)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mapping_questions');
    }
}
