<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalisesLaboratoriaisCreateFormRequest extends FormRequest
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
            'data_analise'                 => 'required',
            'tanque_tipo_id'               => 'required',
            'analise_laboratorial_tipo_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'data_analise.required'                 => 'O campo "Data da análise" deve ser informado.',
            'tanque_tipo_id.required'               => 'O campo "Tipo de tanque" deve ser informado.',
            'analise_laboratorial_tipo_id.required' => 'O campo "Tipo de análise" deve ser informado.',
        ];
    }
}
