<?php

namespace Hni\Dwsync\Http\Requests\API;

use Hni\Dwsync\Models\DwProject;
use InfyOm\Generator\Request\APIRequest;

class UpdateDwProjectAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return DwProject::$rules;
    }
}
