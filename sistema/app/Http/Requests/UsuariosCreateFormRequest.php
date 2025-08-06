<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuariosCreateFormRequest extends FormRequest
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
            'email'            => 'required|string|email|max:100',
            'username'         => 'required|string|max:50|unique:usuarios,username',
            'password'         => 'required|min:8|max:100',
            'password_confirmation' => 'required|same:password',
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
            'username.unique'           => 'O login informada já está associado a outro usuário.',
            
            'password.required'         => 'O campo "Senha" deve ser informada.',
            'password.min'              => 'O campo "Senha" deve possuir no minimo :min caracteres.',
            'password.max'              => 'O campo "Senha" deve possuir no máximo :max caracteres.',

            'password_confirmation.required' => 'O campo "Confirmar senha" deve ser informada.',
            'password_confirmation.same'     => 'As senhas informadas devem ser correspondentes.',
        ];
    }
}
