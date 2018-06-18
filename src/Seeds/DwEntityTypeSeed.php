<?php
namespace Hni\Dwsync\Seeds;

use Illuminate\Database\Seeder;
use Hni\Dwsync\Models\DwEntityType;

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
        DwEntityType::create(['type' => 'Q', 'comment'=>'Questionnaire', 'apiUrl' => '/feeds/']);
        DwEntityType::create(['type' => 'I', 'comment'=>'Idnr', 'apiUrl' => '/api/get_for_form/']);
        DwEntityType::create(['type' => 'DS', 'comment'=>'Datasender', 'apiUrl' => '/api/get_for_form/']);
    }
}
