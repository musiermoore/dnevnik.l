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
            // group
            'group'         => 'required_if:roles,3'
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
            'lastname.required' => 'Поле обязательно для заполнения',
            'firstname.required' => 'Поле обязательно для заполнения',
            'patronymic.required' => 'Поле обязательно для заполнения',
            'birthday.required' => 'Выберите дату рождения',
            'gender.required' => 'Поле обязательно для заполнения',
            'phone.required' => 'Поле обязательно для заполнения',
            'login.required' => 'Поле обязательно для заполнения',
            'email.required' => 'Поле обязательно для заполнения',
            'password.required' => 'Поле обязательно для заполнения',
            'roles.required' => 'Пользователь не может быть без роли',
            'group.required_if' => 'У студента должна быть группа',

            // other
            'email.email' => 'Некорректный ввод почтового ящика. Пример: example@mail.ru',
            'password.confirmed' => 'Подтвердите пароль',
            'phone.regex'       => 'Заполните поле в формате, начиная с 8 и десять цифр после',

            // unique
            'login.unique' => 'Пользователь с таким логином существует',
            'email.unique' => 'Пользователь с таким почтовым адресом существует',
        ];
    }
}
