<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientesFornecedoresEditFormRequest extends FormRequest
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
            'nome'       => 'required|max:100',
            'razao'      => 'required|max:100',
            'cnpj'       => 'required|digits_between:1,15',
            'ie'         => 'required|digits_between:1,15',
            'cep'        => 'required|digits_between:1,10',
            'logradouro' => 'required|max:100',
            'pais_id'    => 'required',
			'estado_id'  => 'required',
			'cidade_id'  => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nome.required'       => 'O campo "Nome fantasia" deve ser informado.',
            'nome.max'            => 'O campo "Nome fantasia" deve possuir no máximo :max caracteres.',

            'razao.required'      => 'O campo "Razão social" deve ser informado.',
            'razao.max'           => 'O campo "Razão social" deve possuir no máximo :max caracteres.',

            'cnpj.required'       => 'O campo "CNPJ" deve ser informado.',
            'cnpj.digits_between' => 'O campo "CNPJ" deve possuir entre :min e :max dígitos.',

            'ie.required'         => 'O campo "I.E." deve ser informado.',
            'ie.digits_between'   => 'O campo "I.E." deve possuir entre :min e :max dígitos.',
            
            'cep.required'        => 'O campo "CEP" deve ser informado.',
            'cep.digits_between'  => 'O campo "CEP" deve possuir entre :min e :max dígitos.',

			'logradouro.required' => 'O campo "Logradouro" deve ser informado.',
            'logradouro.max'      => 'O campo "Logradouro" deve possuir no máximo :max caracteres.',
			
			'pais_id.required'    => 'O campo "Pais" deve ser informado.',
            
            'estado_id.required'  => 'O campo "Estado" deve ser informado.',
            
            'cidade_id.required'  => 'O campo "Cidade" deve ser informado.',
        ];
    }
}
