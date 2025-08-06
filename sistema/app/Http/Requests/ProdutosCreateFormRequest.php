<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdutosCreateFormRequest extends FormRequest
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
            'nome'               => 'required|max:100',
            'sigla'              => 'max:20',
            'unidade_entrada_id' => 'required',
            'unidade_saida_id'   => 'required',
            'unidade_razao'      => 'required',
            'produto_tipo_id'    => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O campo "Nome" deve ser informado.',
            'nome.max'      => 'O campo "Nome" deve possuir no máximo :max caracteres.',

            'sigla.max'      => 'O campo "Sigla" deve possuir no máximo :max caracteres.',

            'unidade_entrada_id.required' => 'O campo "Unidade de entrada" deve ser informado.',
            'unidade_saida_id.required'   => 'O campo "Unidade de saída" deve ser informado.',
            'unidade_razao.required'      => 'O campo "Razão entre unidades" deve ser informado.',
            'produto_tipo_id.required'    => 'O campo "Tipo do produto" deve ser informado.',
        ];
    }
}
