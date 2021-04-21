<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RateRequest extends FormRequest
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
            'student_id'     => ['required', 'exists:users,id'],
            'lesson_id'      => ['required', 'exists:timetables,id'],
            'rate'           => ['required', 'digits_between:1,5'],
        ];
    }
}
