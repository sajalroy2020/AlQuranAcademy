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
        $rules = [
            "date" => ['bail','required'],
            "teacher_id" => ['bail','required'],
            "course_id" => ['bail','required'],
            "start_time" => ['bail','required'],
            "end_time" => ['bail','required'],
        ];
        return $rules;
    }
}
