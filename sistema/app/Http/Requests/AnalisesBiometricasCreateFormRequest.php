<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalisesBiometricasCreateFormRequest extends FormRequest
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
            'data_analise'   => 'required',
            'ciclo_id'       => 'required',
        ];
    }

    public function messages()
    {
        return [
            'data_analise.required'   => 'O campo "Data da análise" deve ser informado.',
            'ciclo_id.required'       => 'O campo "Ciclo" deve ser informado.',
        ];
    }
}
