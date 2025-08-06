<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReprodutoresCreateFormRequest extends FormRequest
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
            'item_tubo'            => 'required',
            'sexo'                 => 'required',
            'numero_anel'          => 'required',
            'cor_anel'             => 'required',
        ];
    }

    public function messages()
    {
        return [
            'item_tubo.required'            => 'O campo "Item Tubo" deve ser informado.',
            'sexo.required'                 => 'O campo "Plantel" deve ser informado.',
            'familia.required'              => 'O campo "Familia" deve ser informado.',
            'numero_anel.required'          => 'O campo "Data Estresse" deve ser informado.',
        ];
    }
}
