<?php

namespace Hni\Dwsync\Models;
use Illuminate\Support\Facades\DB;
use Eloquent as Model;

/**
 * Class MappingProject
 * @package Hni\Dwsync\Models
 * @version October 12, 2017, 8:30 am UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection MappingQuestion
 * @property integer project1
 * @property integer project2
 * @property string dateLastExported
 * @property tinyInteger isActive
 */
class MappingProject extends Model
{

    public $table = 'mapping_projects';
    public $timestamps = false; /* forced to be false  */


    public $fillable = [
        'project1',
        'project2',
        'dateLastExported',
        'isActive'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'project1' => 'integer',
        'project2' => 'integer',
        'dateLastExported' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'project1' => 'nullable|min:0',
        'project2' => 'nullable|min:0',
        'dateLastExported' => 'nullable',
        'isActive' => 'min:0|max:1'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function mappingQuestions()
    {
        return $this->hasMany(\Hni\Dwsync\Models\MappingQuestion::class, 'mappingProjectId', 'id');
    }

    public static function getAll(){
        return DB::select("SELECT
                m.*
                ,d1.comment as name_project1
                ,d2.comment as name_project2
                ,t1.comment as type_project1
                ,t2.comment as type_project2
                FROM mapping_projects m
                INNER JOIN dw_projects d1 on d1.id = m.project1
                INNER JOIN dw_projects d2 on d2.id = m.project2
                INNER JOIN dw_entity_types t1 on t1.type = d1.entityType
                INNER JOIN dw_entity_types t2 on t2.type = d2.entityType");
    }

    public static function getAllForSelect(){
        $result = array();
        $maps = DB::select("SELECT
                m.*
                ,d1.comment as name_project1
                ,d2.comment as name_project2
                ,t1.comment as type_project1
                ,t2.comment as type_project2
                FROM mapping_projects m
                INNER JOIN dw_projects d1 on d1.id = m.project1
                INNER JOIN dw_projects d2 on d2.id = m.project2
                INNER JOIN dw_entity_types t1 on t1.type = d1.entityType
                INNER JOIN dw_entity_types t2 on t2.type = d2.entityType");

        foreach ($maps as $map) {
            $result[$map->id] =  "[".$map->type_project1.":".$map->name_project1."]"
                ." to [".$map->type_project2.":". $map->name_project2."]";
        }
        return $result;
    }
}
