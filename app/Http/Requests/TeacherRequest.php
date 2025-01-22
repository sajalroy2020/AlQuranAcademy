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
            'dob' => ['required', 'date'],
            'password' => 'bail|required|min:6',
            'course_id' => ['required', 'array'],
            "course_id.*" => ['bail','required'],
            "father_name" => ['bail','required'],
            "marital_status" => ['bail','required'],
            "present_address" => ['bail','required'],
            "permanent_address" => ['bail','required'],
            "edu_qualification" => ['bail','required'],
            "training_qualification" => ['bail','required'],
            "other_occupation" => ['bail','required'],
            "occupation_details" => ['bail','required'],
            "class_device" => ['bail','required'],
            "is_all_agree" => ['bail','required'],
        ];

        if(!$id){
            $rules["certificate_file"] = ['bail','required', 'file', 'mimes:pdf,jpeg,jpg,png'];
            $rules["nid_file"] = ['bail','required', 'file', 'mimes:pdf,jpeg,jpg,png'];
        }

        if($this->gender == GENDER_FEMALE){
            $rules["guardian_phone"] = ['bail','required','numeric', Rule::unique('teacher_details','guardian_phone')->ignore($id, 'id')->whereNull('deleted_at')];
        }
        
        return $rules;
    }

    public function messages()
    {
        return [
            'course_id.*.required' => __('The course field is required'),
        ];
    }

}
