<?php

namespace Hni\Dwsync\Repositories;

use Hni\Dwsync\Models\DwProject;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class DwProjectRepository
 * @package Hni\Dwsync\Repositories
 * @version September 20, 2017, 11:02 pm UTC
 *
 * @method DwProject findWithoutFail($id, $columns = ['*'])
 * @method DwProject find($id, $columns = ['*'])
 * @method DwProject first($columns = ['*'])
*/
class DwProjectRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'questCode',
        'submissionTable',
        'comment',
        'xformUrl',
        'entityType'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DwProject::class;
    }
}
