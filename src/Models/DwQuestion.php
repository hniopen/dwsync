<?php

namespace Hni\Dwsync\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * Class DwQuestion
 * @package Hni\Dwsync\Models
 * @version September 21, 2017, 1:31 pm UTC
 *
 * @property \Hni\Dwsync\Models\DwProject dwProject
 * @property string xformQuestionId
 * @property string questionId
 * @property string path
 * @property string labelDefault
 * @property string labelFr
 * @property string labelUs
 * @property string dataType
 * @property string dataFormat
 * @property integer order
 * @property string linkedIdnr
 * @property string periodType
 * @property string periodTypeFormat
 * @property tinyInteger isUnique
 * @property tinyInteger isMigrated
 */
class DwQuestion extends Model
{
//    use SoftDeletes;

    public $table = 'dw_questions';
    public $timestamps = false;

    protected $dates = ['deleted_at'];


    public $fillable = [
        'projectId',
        'xformQuestionId',
        'questionId',
        'path',
        'labelDefault',
        'labelFr',
        'labelUs',
        'dataType',
        'dataFormat',
        'order',
        'linkedIdnr',
        'periodType',
        'periodTypeFormat',
        'isUnique',
        'isMigrated'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'projectId' => 'integer',
        'xformQuestionId' => 'string',
        'questionId' => 'string',
        'path' => 'string',
        'labelDefault' => 'string',
        'labelFr' => 'string',
        'labelUs' => 'string',
        'dataType' => 'string',
        'dataFormat' => 'string',
        'order' => 'integer',
        'linkedIdnr' => 'string',
        'periodType' => 'string',
        'periodTypeFormat' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'projectId' => 'nullable|min:0',
        'xformQuestionId' => 'nullable',
        'questionId' => 'nullable',
        'path' => 'nullable',
        'labelDefault' => 'nullable',
        'labelFr' => 'nullable',
        'labelUs' => 'nullable',
        'dataType' => 'nullable',
        'dataFormat' => 'nullable',
        'order' => 'nullable',
        'linkedIdnr' => 'nullable',
        'periodType' => 'nullable',
        'periodTypeFormat' => 'nullable',
        'isUnique' => 'min:0|max:1',
        'isMigrated' => 'min:0|max:1'
    ];

    public static function boot()
    {
        static::creating(function ($model) {
            $model->calculateQuestionId();
        });

        static::updating(function ($model) {
            $model->calculateQuestionId();
        });

        static::deleting(function ($model) {
            // bluh bluh
        });

        parent::boot();
    }

    private function calculateQuestionId(){
        $_questId = $this->projectId . "#". $this->xformQuestionId;
        $this->questionId = $_questId;
        $correpsondingDwValues = $this->dwSubmissionValues($this->questCode)->get();
        foreach ($correpsondingDwValues as $values){
            $values->questionId = $_questId;
            $values->save();
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dwProject()
    {
        return $this->belongsTo(\Hni\Dwsync\Models\DwProject::class, 'projectId', 'id');
    }

    public static function getAllForSelect(){
        $result = array();
        $questions =
            DB::select("SELECT
                d.*
                ,d1.comment as name_project
                ,t1.comment as type_project
                FROM dw_questions d
                INNER JOIN dw_projects d1 on d1.id = d.projectId
                INNER JOIN dw_entity_types t1 on t1.type = d1.entityType 
                ORDER BY d1.comment,d.labelDefault;");

        foreach ($questions as $question) {
            $result[$question->id] =  "[".$question->type_project.":".$question->name_project."] - " .$question->labelDefault;
        }
        return $result;
    }
}
