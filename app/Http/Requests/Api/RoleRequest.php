<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class RoleRequest extends FormRequest
{


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'roles' => ['required', 'exists:spatie_roles,id'],
        ];
    }

    public function messages()
    {
        return [
            'roles.required'    => 'The role field is required.',
            'roles.exists'      => 'There are no roles named.',
        ];
    }
}
