<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalisesBiometricasV2CreateFormRequest extends FormRequest
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
            'ciclo_id'        => 'required',
            'data_analise'    => 'required',
            'peso_total'      => 'required',
        ];
    }

    public function messages()
    {
        return [
            
            'ciclo_id.required' => 'O campo "Ciclo" deve ser informado.',
            'data_analise.required'   => 'O campo "Data da análise" deve ser informado.',
            'peso_total.required'     => 'O campo "Peso Total" deve ser informado.',

        ];
    }
}
