<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
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
        return [
            'name'              => ['required', 'string', 'unique:groups'],
            'curator_id'        => ['required', 'exists:users,id'],
            'enrollment_date'   => ['required', 'date'],
            'duration_study'    => ['required', 'integer'],
        ];
    }
}
