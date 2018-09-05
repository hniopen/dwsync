<?php
namespace Hni\Dwsync\Seeds;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DwProjectsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('dw_projects')->delete();
        
        \DB::table('dw_projects')->insert(array (
            0 => 
            array (
                'id' => 1,
                'questCode' => 'reg',
                'submissionTable' => NULL,
                'parentId' => NULL,
                'comment' => 'Datasender',
                'isDisplayed' => 1,
                'xformUrl' => NULL,
                'credential' => 'eyJpdiI6IldKd3EzY3M2RVJhdklsQmtLUlNkRmc9PSIsInZhbHVlIjoiUndrZnRtdG81ektsdWY0dXBCeFVXOGJhUmdsZTBTQit0Yzd1SnJGZGE2RUhiY2dHRGloSVZITmZ1YmNROGtTcCIsIm1hYyI6IjFlNWQ4NWMwYmJmYzg1MWFjOWZiYWQyNjg3YzY2ZmFhMWQ1NGU1OWZmZTlmYWFmZjE4MjMxNTNiZThhZDY3NjgifQ==',
                'entityType' => 'DS',
                'formType' => 'basic',
                'deleted_at' => NULL,
                'longQuestCode' => NULL,
            ),
        ));
        
        
    }
}