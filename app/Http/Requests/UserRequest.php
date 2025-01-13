<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        return [
            'name' => ['required', 'max:120'],
            "email" => ['bail','required','email', Rule::unique('users','email')->whereNull('deleted_at')],
            "phone" => ['bail','required','numeric', Rule::unique('users','phone')->whereNull('deleted_at')],
            'gender' => ['required'],
            'course_id' => ['required'],
            'country_id' => ['required'],
            'state_id' => ['required'],
            'dob' => ['required', 'date'],
            'password' => 'bail|required|min:6',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'The email field is required.',
        ];
    }
}
