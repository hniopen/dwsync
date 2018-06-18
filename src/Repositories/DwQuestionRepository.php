<?php

namespace Hni\Dwsync\Repositories;

use Hni\Dwsync\Models\DwQuestion;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class DwQuestionRepository
 * @package Hni\Dwsync\Repositories
 * @version September 20, 2017, 11:25 pm UTC
 *
 * @method DwQuestion findWithoutFail($id, $columns = ['*'])
 * @method DwQuestion find($id, $columns = ['*'])
 * @method DwQuestion first($columns = ['*'])
*/
class DwQuestionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
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
        'periodTypeFormat'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DwQuestion::class;
    }
}
