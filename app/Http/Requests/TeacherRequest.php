<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeacherRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $id = isset($this->id)?$this->id:null;

        $rules = [
            'name' => ['required', 'max:120'],
            "email" => ['bail','required','email', Rule::unique('users','email')->ignore($id, 'id')->whereNull('deleted_at')],
            "phone" => ['bail','required','numeric', Rule::unique('users','phone')->ignore($id, 'id')->whereNull('deleted_at')],
            'gender' => ['required'],
            'country_id' => ['required'],
            'state_id' => ['required'],
            'dob' => ['required', 'date'],
            'password' => 'bail|required|min:6',
            'course_id' => ['required', 'array'],
            "course_id.*" => ['bail','required'],
        ];
        
        return $rules;
    }

    public function messages()
    {
        return [
            'course_id.*.required' => __('The course field is required'),
        ];
    }

}
