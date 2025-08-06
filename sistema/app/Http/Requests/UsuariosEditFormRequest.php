<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PasswordValidateRule;

class UsuariosEditFormRequest extends FormRequest
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
            'nome'             => 'required|string|min:5|max:50',
            'email'            => 'required|email|max:100',
            'username'         => 'required|string|max:50',
            'password'         => ['max:100', new PasswordValidateRule],
            'password_confirmation' => 'same:password',
        ];
    }

    public function messages()
    {
        return [
            'nome.required'             => 'O campo "Nome" deve ser informado.',
            'nome.min'                  => 'O campo "Nome" deve possuir no minimo :min caracteres.',
            'nome.max'                  => 'O campo "Nome" deve possuir no máximo :max caracteres.',

            'email.required'            => 'O campo "E-mail" deve ser informado.',
            'email.max'                 => 'O campo "E-mail" deve possuir no máximo :max caracteres.',
            
            'username.required'         => 'O campo "Login" deve ser informado.',
            'username.max'              => 'O campo "Login" deve possuir no máximo :max caracteres.',

            'password.max'              => 'O campo "Senha" deve possuir no máximo :max caracteres.',
             
            'password_confirmation.same'     => 'As senhas informadas devem ser correspondentes.',
        ];
    }
}
