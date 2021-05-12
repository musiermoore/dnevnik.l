<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    protected $container = [];

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
            // profile
            'lastname'      => 'required|string|max:64',
            'firstname'     => 'required|string|max:64',
            'patronymic'    => 'required|string|max:64',
            'birthday'      => 'required|date',
            'gender'        => 'required|boolean',
            'phone'         => 'required|regex:/(8)[0-9]{10}/|size:11   ',
            // user
            'login'         => 'required|string|max:32|unique:users',
            'email'         => 'required|string|max:32|unique:users|email',
            'password'      => 'required|string|max:32|confirmed',
            // role
            'roles'         => 'required|exists:spatie_roles,id',
        ];
    }
}
