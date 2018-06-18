<?php

namespace Hni\Dwsync\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DwEntityType
 * @package Hni\Dwsync\Models
 * @version September 21, 2017, 8:11 am UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection DwProject
 * @property string type
 * @property string comment
 * @property string apiUrl
 */
class DwEntityType extends Model
{
//    use SoftDeletes;

    public $table = 'dw_entity_types';
    public $timestamps = false;

    protected $dates = ['deleted_at'];


    public $fillable = [
        'type',
        'comment',
        'apiUrl'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'type' => 'string',
        'comment' => 'string',
        'apiUrl' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'type' => 'required|unique:dw_entity_types',
        'comment' => 'required',
        'apiUrl' => 'required:url'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function dwProjects()
    {
        return $this->hasMany(\Hni\Dwsync\Models\DwProject::class, 'entityType', 'type');
    }
}
