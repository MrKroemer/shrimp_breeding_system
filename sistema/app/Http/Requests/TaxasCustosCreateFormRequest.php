<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaxasCustosCreateFormRequest extends FormRequest
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
            'data_referencia'   => 'required',
            'custo_fixo'        => 'numeric',
            'custo_energia'     => 'numeric',
            'custo_combustivel' => 'numeric',
            'custo_depreciacao' => 'numeric',
            /*'custovar_racao'    => 'numeric',
            'custofix_racao'    => 'numeric',*/
        ];
    }

    public function messages()
    {
        return [
            'data_referencia.required'  => 'O campo "Data de referência" deve ser informado.',

            'custo_fixo.numeric'   => 'O campo "Taxa de custos fixos" deve possuir apenas números.',

            'custo_energia.numeric' => 'O campo "Taxa de energia" deve possuir apenas números.',

            'custo_combustivel.numeric' => 'O campo "Taxa de combustível" deve possuir apenas números.',

            'custo_depreciacao.numeric'  => 'O campo "Taxa de depreciação" deve possuir apenas números.',
        ];
    }
}
