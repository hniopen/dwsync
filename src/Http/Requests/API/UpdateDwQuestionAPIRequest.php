<?php

namespace Hni\Dwsync\Http\Requests\API;

use Hni\Dwsync\Models\DwQuestion;
use InfyOm\Generator\Request\APIRequest;

class UpdateDwQuestionAPIRequest extends APIRequest
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
        return DwQuestion::$rules;
    }
}