<?php

namespace Hni\Dwsync\Repositories;

use Hni\Dwsync\Models\DwSms;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class DwSmsRepository
 * @package Hni\Dwsync\Repositories
 * @version September 20, 2017, 11:02 pm UTC
 *
 * @method DwSms findWithoutFail($id, $columns = ['*'])
 * @method DwSms find($id, $columns = ['*'])
 * @method DwSms first($columns = ['*'])
*/
class DwSmsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'date',
        'recipient',
        'content',
        'status',
        'curl_error_no',
        'curl_error'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return DwSms::class;
    }
}
