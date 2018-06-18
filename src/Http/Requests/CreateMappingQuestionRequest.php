<?php

namespace Hni\Dwsync\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hni\Dwsync\Models\MappingQuestion;

class CreateMappingQuestionRequest extends FormRequest
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
        return MappingQuestion::$rules;
    }
}
