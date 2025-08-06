<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArracoamentosEsquemasCreateFormRequest extends FormRequest
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
            'dia_inicial' => 'required|numeric',
            'dia_final'   => 'required|numeric',
            'periodo'     => 'required',
        ];
    }

    public function messages()
    {
        return [
            'dia_inicial.required' => 'O campo "Primeiro dia" deve ser informado.',
            'dia_inicial.numeric'  => 'O campo "Primeiro dia" deve ser numérico.',

            'dia_final.required' => 'O campo "Último dia" deve ser informado.',
            'dia_final.numeric'  => 'O campo "Último dia" deve deve ser numérico.',

            'periodo.required'   => 'O campo "Período" deve ser informado.',
        ];
    }
}
