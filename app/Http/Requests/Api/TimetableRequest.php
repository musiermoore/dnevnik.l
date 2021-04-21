<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TimetableRequest extends FormRequest
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
            'subject'           => ['required', 'exists:subjects,id'],
            'classroom'         => ['required', 'exists:classrooms,id'],
            'lesson_number'     => ['required', 'exists:lesson_numbers,id'],
            'group'             => ['required', 'exists:groups,id'],
            'teacher'           => ['required', 'exists:users,id'],
            'weekday'           => ['required', 'exists:weekdays,id'],
            'date'              => ['required', 'date'],
        ];
    }
}
