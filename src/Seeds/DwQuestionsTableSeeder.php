<?php
namespace Hni\Dwsync\Seeds;
use Illuminate\Database\Seeder;

class DwQuestionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('dw_questions')->delete();
        
        \DB::table('dw_questions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'projectId' => 1,
                'xformQuestionId' => 'short_code',
                'questionId' => NULL,
                'path' => NULL,
                'labelDefault' => NULL,
                'labelFr' => NULL,
                'labelUs' => NULL,
                'dataType' => NULL,
                'dataFormat' => NULL,
                'order' => NULL,
                'linkedIdnr' => NULL,
                'periodType' => NULL,
                'periodTypeFormat' => NULL,
                'isUnique' => 0,
                'isMigrated' => 0,
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'projectId' => 1,
                'xformQuestionId' => 'mobile_number',
                'questionId' => NULL,
                'path' => NULL,
                'labelDefault' => NULL,
                'labelFr' => NULL,
                'labelUs' => NULL,
                'dataType' => NULL,
                'dataFormat' => NULL,
                'order' => NULL,
                'linkedIdnr' => NULL,
                'periodType' => NULL,
                'periodTypeFormat' => NULL,
                'isUnique' => 0,
                'isMigrated' => 0,
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'projectId' => 1,
                'xformQuestionId' => 'location',
                'questionId' => NULL,
                'path' => NULL,
                'labelDefault' => NULL,
                'labelFr' => NULL,
                'labelUs' => NULL,
                'dataType' => NULL,
                'dataFormat' => NULL,
                'order' => NULL,
                'linkedIdnr' => NULL,
                'periodType' => NULL,
                'periodTypeFormat' => NULL,
                'isUnique' => 0,
                'isMigrated' => 0,
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'projectId' => 1,
                'xformQuestionId' => 'name',
                'questionId' => NULL,
                'path' => NULL,
                'labelDefault' => NULL,
                'labelFr' => NULL,
                'labelUs' => NULL,
                'dataType' => NULL,
                'dataFormat' => NULL,
                'order' => NULL,
                'linkedIdnr' => NULL,
                'periodType' => NULL,
                'periodTypeFormat' => NULL,
                'isUnique' => 0,
                'isMigrated' => 0,
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'projectId' => 1,
                'xformQuestionId' => 'entity_type',
                'questionId' => NULL,
                'path' => NULL,
                'labelDefault' => NULL,
                'labelFr' => NULL,
                'labelUs' => NULL,
                'dataType' => NULL,
                'dataFormat' => NULL,
                'order' => NULL,
                'linkedIdnr' => NULL,
                'periodType' => NULL,
                'periodTypeFormat' => NULL,
                'isUnique' => 0,
                'isMigrated' => 0,
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'projectId' => 1,
                'xformQuestionId' => 'geo_code',
                'questionId' => NULL,
                'path' => NULL,
                'labelDefault' => NULL,
                'labelFr' => NULL,
                'labelUs' => NULL,
                'dataType' => NULL,
                'dataFormat' => NULL,
                'order' => NULL,
                'linkedIdnr' => NULL,
                'periodType' => NULL,
                'periodTypeFormat' => NULL,
                'isUnique' => 0,
                'isMigrated' => 0,
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'projectId' => 1,
                'xformQuestionId' => 'email',
                'questionId' => NULL,
                'path' => NULL,
                'labelDefault' => NULL,
                'labelFr' => NULL,
                'labelUs' => NULL,
                'dataType' => NULL,
                'dataFormat' => NULL,
                'order' => NULL,
                'linkedIdnr' => NULL,
                'periodType' => NULL,
                'periodTypeFormat' => NULL,
                'isUnique' => 0,
                'isMigrated' => 0,
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'projectId' => 1,
                'xformQuestionId' => 'is_data_sender',
                'questionId' => NULL,
                'path' => NULL,
                'labelDefault' => NULL,
                'labelFr' => NULL,
                'labelUs' => NULL,
                'dataType' => NULL,
                'dataFormat' => NULL,
                'order' => NULL,
                'linkedIdnr' => NULL,
                'periodType' => NULL,
                'periodTypeFormat' => NULL,
                'isUnique' => 0,
                'isMigrated' => 0,
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}