<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnidadesMedidasCreateFormRequest extends FormRequest
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
            'unidade_nome'  => 'required|max:50|unique:unidades_medidas,nome',
            'unidade_sigla' => 'required|max:20|unique:unidades_medidas,sigla',
        ];
    }

    public function messages()
    {
        return [
            'unidade_nome.required'   => 'Para criar uma nova unidade de medida, um nome deve informado.',
            'unidade_nome.max'        => 'O nome da unidade de medida, deve possuir no máximo :max caracteres.',
            'unidade_nome.unique'     => 'Esse unidade de medida já existe.',

            'unidade_sigla.required'  => 'Para criar uma nova unidade de medida, uma sigla deve informado.',
            'unidade_sigla.max'       => 'A sigla da unidade de medida, deve possuir no máximo :max caracteres.',
            'unidade_sigla.unique'    => 'A sigla informada para esta unidade de medida já existe.',
        ];
    }
}
