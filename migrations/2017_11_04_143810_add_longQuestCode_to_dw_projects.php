<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLongQuestCodeToDwProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dw_projects', function (Blueprint $table) {
            $table->string('longQuestCode', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dw_projects', function (Blueprint $table) {
            $table->dropColumn('longQuestCode');
        });
    }
}
