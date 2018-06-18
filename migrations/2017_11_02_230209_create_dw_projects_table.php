<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDwProjectsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dw_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('questCode', 20);
            $table->string('submissionTable')->nullable();
            $table->integer('parentId')->nullable();
            $table->text('comment')->nullable();
            $table->tinyInteger('isDisplayed')->default(1);
            $table->string('xformUrl', 100)->nullable();
            $table->string('credential');
            $table->string('entityType', 4);
            $table->string('formType');
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
        Schema::drop('dw_projects');
    }
}
