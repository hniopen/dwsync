<?php

namespace Hni\Dwsync\Repositories;

use Hni\Dwsync\Models\DwEntityType;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class DwEntityTypeRepository
 * @package Hni\Dwsync\Repositories
 * @version September 21, 2017, 8:11 am UTC
 *
 * @method DwEntityType findWithoutFail($id, $columns = ['*'])
 * @method DwEntityType find($id, $columns = ['*'])
 * @method DwEntityType first($columns = ['*'])
*/
class DwEntityTypeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'comment',
        'apiUrl'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DwEntityType::class;
    }
}
