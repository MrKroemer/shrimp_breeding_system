<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParametrosCreateFormRequest extends FormRequest
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
            'sigla'             => 'required|max:5',
            'descricao'         => 'required|max:50',
            'unidade_medida_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'descricao.required' => 'O campo "Descrição" deve ser informado.',
            'descricao.max'      => 'O campo "Descrição" deve possuir no máximo :max caracteres.',

            'sigla.required' => 'O campo "Sigla" deve ser informado.',
            'sigla.max'      => 'O campo "Sigla" deve possuir no máximo :max caracteres.',

            'unidade_medida_id.required' => 'O campo "Unidade de medida" deve ser informado.'
        ];
    }
}
