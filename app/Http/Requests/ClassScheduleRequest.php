<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClassScheduleRequest extends FormRequest
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
            "days.*" => ['bail','required'],
            "teacher_id" => ['bail','required'],
            "course_id" => ['bail','required'],
           
        ];
        
        if (!$id) {
            $rules["start_time.*"] = ['bail', 'required'];
            $rules["end_time.*"] = ['bail', 'required'];  
        } else {
            $rules["start_time"] = ['bail', 'required'];
            $rules["end_time"] = ['bail', 'required'];  
        }

        return $rules;
    }

    public function messages()
    {
        return [
            "days.*.required" => ['Day field is required'],
            'start_time.*.required' => __('This field is required'),
            'end_time.*.required' => __('This field is required'),
        ];
    }
}
