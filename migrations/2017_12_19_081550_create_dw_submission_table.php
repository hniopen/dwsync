<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDwSubmissionTable extends Migration
{
    /**
     *
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('dw_submission');
        Schema::create('dw_submission', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('projectId');
            $table->string('submissionId', 50);
            $table->string('dwSubmittedAt');
            $table->string('dwSubmittedAt_u', 20);
            $table->string('dwUpdatedAt')->nullable();
            $table->string('dwUpdatedAt_u', 20)->nullable();
            $table->string('status', 20)->nullable();
            $table->tinyInteger('isValid')->default(0);
            $table->string('datasenderId', 10)->nullable();
            $table->string('cleanFlag', 30)->nullable();
            $table->string('pushIdnrStatus', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dw_submission');
    }
}
