<?php

namespace Hni\Dwsync\Repositories;

use Hni\Dwsync\Models\MappingProject;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class MappingProjectRepository
 * @package Hni\Dwsync\Repositories
 * @version October 12, 2017, 8:30 am UTC
 *
 * @method MappingProject findWithoutFail($id, $columns = ['*'])
 * @method MappingProject find($id, $columns = ['*'])
 * @method MappingProject first($columns = ['*'])
*/
class MappingProjectRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return MappingProject::class;
    }
}
