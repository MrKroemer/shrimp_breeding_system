<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FiliaisEditFormRequest extends FormRequest
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
            'nome'     => 'required|max:50',
            'cidade'   => 'required|max:30',
            'endereco' => 'max:100',
            'cnpj'     => 'required|digits_between:1,15',
        ];
    }

    public function messages()
    {
        return [
            'nome.required'   => 'O campo "Nome" deve ser informado.',
            'nome.max'        => 'O campo "Nome" deve possuir no máximo :max caracteres.',

            'cidade.required' => 'O campo "Cidade" deve ser informado.',
            'cidade.max'      => 'O campo "Cidade" deve possuir no máximo :max caracteres.',

            'endereco.max'    => 'O campo "Endereço" deve possuir no máximo :max caracteres.',
            
            'cnpj.required'       => 'O campo "CNPJ" deve ser informado.',
            'cnpj.digits_between' => 'O campo "CNPJ" deve possuir entre :min e :max dígitos.',
        ];
    }
}
