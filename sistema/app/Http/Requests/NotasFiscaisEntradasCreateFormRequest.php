<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotasFiscaisEntradasCreateFormRequest extends FormRequest
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
            /*'chave'          => 'size:44|unique:notas_fiscais,chave',*/
            'numero'         => 'required|numeric',
            'data_emissao'   => 'required',
            'data_movimento' => 'required',
            'valor_total'    => 'required|numeric',
            'valor_frete'    => 'numeric',
            'valor_desconto' => 'numeric',
            'fornecedor_id'  => 'required',
            'cliente_id'     => 'required',
        ];
    }

    public function messages()
    {
        return [
            /*'chave.size'              => 'O campo "Chave" deve ser informado com exatamente :size dígitos.',*/
            'chave.unique'            => 'Já existe uma nota fiscal registrada com essa chave.',

            'numero.required'         => 'O campo "Número da NF" deve ser informado.',
            'numero.numeric'          => 'O campo "Número da NF" deve ser numérico.',

            'data_emissao.required'   => 'O campo "Data de emissão" deve ser informado.',

            'data_movimento.required' => 'O campo "Data de entrada em estoque" deve ser informado.',

            'valor_total.required'    => 'O campo "Valor total da NF" deve ser informado.',
            'valor_total.numeric'     => 'O campo "Valor total da NF" deve ser numérico.',

            'valor_frete.numeric'     => 'O campo "Valor do frete" deve ser numérico.',

            'valor_desconto.numeric'  => 'O campo "Valor total dos descontos" deve ser numérico.',

            'fornecedor_id.required'  => 'O campo "Fornecedor" deve ser informado.',

            'cliente_id.required'     => 'O campo "Cliente" deve ser informado.',
        ];
    }
}
