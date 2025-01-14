<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $id = isset($this->id)?$this->id:null;
        $rules = [
            "subject_name" => ['bail','required', Rule::unique('courses','subject_name')->ignore($id, 'id')->whereNull('deleted_at')],
        ];
        return $rules;
    }
}
