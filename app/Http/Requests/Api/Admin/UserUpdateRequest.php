<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user();
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
            'profile.lastname'      => 'required|string|max:64',
            'profile.firstname'     => 'required|string|max:64',
            'profile.patronymic'    => 'required|string|max:64',
            'profile.birthday'      => 'required|date',
            'profile.gender'        => 'required|boolean',
            'profile.phone'         => 'required|regex:/(8)[0-9]{10}/|size:11   ',
            // user
            'login'         => 'required|string|max:32',
            'email'         => 'required|string|max:32|email',
            'password'      => 'string|max:32|confirmed',
            // role
            'roles'         => 'required|exists:spatie_roles,name',
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
            // required
            'profile.lastname.required' => 'Поле обязательно для заполнения',
            'profile.firstname.required' => 'Поле обязательно для заполнения',
            'profile.patronymic.required' => 'Поле обязательно для заполнения',
            'profile.birthday.required' => 'Выберите дату рождения',
            'profile.gender.required' => 'Поле обязательно для заполнения',
            'profile.phone.required' => 'Поле обязательно для заполнения',
            'login.required' => 'Поле обязательно для заполнения',
            'email.required' => 'Поле обязательно для заполнения',
            'password.required' => 'Поле обязательно для заполнения',
            'roles.required' => 'Пользователь не может быть без роли',
            'group.required_if' => 'У студента должна быть группа',

            // other
            'email.email' => 'Некорректный ввод почтового ящика. Пример: example@mail.ru',
            'password.confirmed' => 'Подтвердите пароль',
            'profile.phone.regex'       => 'Заполните поле в формате, начиная с 8 и десять цифр после',

            // unique
            'login.unique' => 'Пользователь с таким логином существует',
            'email.unique' => 'Пользователь с таким почтовым адресом существует',
        ];
    }
}
