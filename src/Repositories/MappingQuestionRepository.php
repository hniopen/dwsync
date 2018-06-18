<?php

namespace Hni\Dwsync\Repositories;

use Hni\Dwsync\Models\MappingQuestion;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class MappingQuestionRepository
 * @package Hni\Dwsync\Repositories
 * @version October 12, 2017, 8:32 am UTC
 *
 * @method MappingQuestion findWithoutFail($id, $columns = ['*'])
 * @method MappingQuestion find($id, $columns = ['*'])
 * @method MappingQuestion first($columns = ['*'])
*/
class MappingQuestionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'functions',
        'arg1',
        'arg2'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MappingQuestion::class;
    }
}
