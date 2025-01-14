<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StateRequest extends FormRequest
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
            "name" => ['bail','required', Rule::unique('states','name')->ignore($id, 'id')->whereNull('deleted_at')],
            "country_id" => ['bail','required'],
        ];
        return $rules;
    }
}
