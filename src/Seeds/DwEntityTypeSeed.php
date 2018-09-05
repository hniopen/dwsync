<?php
namespace Hni\Dwsync\Seeds;

use Illuminate\Database\Seeder;
use Hni\Dwsync\Models\DwEntityType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DwEntityTypeSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Default types
        Schema::disableForeignKeyConstraints();
        DB::table('dw_entity_types')->truncate();
        DwEntityType::create(['type' => 'Q', 'comment'=>'Questionnaire', 'apiUrl' => '/feeds/']);
        DwEntityType::create(['type' => 'I', 'comment'=>'Idnr', 'apiUrl' => '/api/get_for_form/']);
        DwEntityType::create(['type' => 'DS', 'comment'=>'Datasender', 'apiUrl' => '/api/get_for_form/']);
        Schema::enableForeignKeyConstraints();
    }
}
