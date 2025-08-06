<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalisesBiometricasOldCreateFormRequest extends FormRequest
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
            'ciclo_id'      => 'required',
            'data_analise'  => 'required',
            'total_animais' => 'required|numeric',
            'peso_total'    => 'required|numeric',
            'peso_medio'    => 'required|numeric',
            'sobrevivencia' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'ciclo_id.required' => 'O campo "Ciclo" deve ser informado.',

            'data_analise.required' => 'O campo "Data da análise" deve ser informado.',

            'total_animais.required' => 'O campo "Total de animais" deve ser informado.',
            'total_animais.numeric'  => 'O campo "Total de animais" deve ser numérico.',

            'peso_total.required'    => 'O campo "Peso total" deve ser informado.',
            'peso_total.numeric'     => 'O campo "Peso total" deve ser numérico.',

            'peso_medio.required'    => 'O campo "Peso médio" deve ser informado.',
            'peso_medio.numeric'     => 'O campo "Peso médio" deve ser numérico.',

            'sobrevivencia.required' => 'O campo "Percentual de sobrevivência" deve ser informado.',
            'sobrevivencia.numeric'  => 'O campo "Percentual de sobrevivência" deve ser numérico.',
        ];
    }
}
