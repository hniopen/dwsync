<?php

namespace Hni\Dwsync\Models;
use Illuminate\Support\Facades\DB;

use Eloquent as Model;

/**
 * Class MappingQuestion
 * @package Hni\Dwsync\Models
 * @version October 12, 2017, 8:32 am UTC
 *
 * @property MappingProject mappingProject
 * @property integer mappingProjectId
 * @property integer question1
 * @property integer question2
 * @property string functions
 * @property string arg1
 * @property string arg2
 */
class MappingQuestion extends Model
{

    public $table = 'mapping_questions';
    public $timestamps = false; /* forced to be false  */


    public $fillable = [
        'mappingProjectId',
        'question1',
        'question2',
        'functions',
        'arg1',
        'arg2'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'mappingProjectId' => 'integer',
        'question1' => 'integer',
        'question2' => 'integer',
        'functions' => 'string',
        'arg1' => 'string',
        'arg2' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'mappingProjectId' => 'nullable|min:0',
        'question1' => 'nullable|min:0',
        'question2' => 'nullable|min:0',
        'functions' => 'nullable',
        'arg1' => 'nullable',
        'arg2' => 'nullable'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function mappingProject()
    {
        return $this->belongsTo(\Hni\Dwsync\Models\MappingProject::class, 'mappingProjectId', 'id');
    }


    public static function getAll(){
        return DB::select("SELECT
                m.*
                ,q1.labelDefault as name_question1
                ,q2.labelDefault as name_question2
                ,d1.comment as name_project1
                ,d2.comment as name_project2
                ,t1.comment as type_project1
                ,t2.comment as type_project2
                FROM mapping_questions m
                LEFT JOIN dw_questions q1 on q1.id = m.question1
                LEFT JOIN dw_questions q2 on q2.id = m.question2
                LEFT JOIN mapping_projects p on p.id = m.mappingProjectId
                LEFT JOIN dw_projects d1 on d1.id = p.project1
                LEFT JOIN dw_projects d2 on d2.id = p.project2
                LEFT JOIN dw_entity_types t1 on t1.type = d1.entityType
                LEFT JOIN dw_entity_types t2 on t2.type = d2.entityType");
    }
}
