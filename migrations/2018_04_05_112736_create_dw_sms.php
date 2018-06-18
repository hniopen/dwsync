<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDwSms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dw_sms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('date', 20);
            $table->string('recipient', 20);
            $table->text('content')->nullable();
            $table->text('status')->nullable();
            $table->integer('curl_error_no')->nullable();
            $table->text('curl_error')->nullable();
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
        Schema::drop('dw_sms');
    }
}
