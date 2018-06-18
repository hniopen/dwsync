<?php

use Illuminate\Database\Migrations\Migration;

class AlterDwSubmissionTableToMyisam extends Migration
{
    /**
     *
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE dw_submission ENGINE = MyISAM');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE dw_submission ENGINE = InnoDB');
    }
}
