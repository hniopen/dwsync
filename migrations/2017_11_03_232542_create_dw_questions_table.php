<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDwQuestionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dw_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('projectId')->nullable();
            $table->string('xformQuestionId')->nullable();
            $table->string('questionId')->nullable();
            $table->string('path')->nullable();
            $table->text('labelDefault')->nullable();
            $table->text('labelFr')->nullable();
            $table->text('labelUs')->nullable();
            $table->string('dataType', 30)->nullable();
            $table->string('dataFormat', 30)->nullable();
            $table->integer('order')->nullable();
            $table->string('linkedIdnr', 20)->nullable();
            $table->string('periodType', 20)->nullable();
            $table->string('periodTypeFormat', 20)->nullable();
            $table->tinyInteger('isUnique')->default(0);
            $table->tinyInteger('isMigrated')->default(0);
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dw_questions');
    }
}
