<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColetasParametrosNewCreateFormRequest extends FormRequest
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
            'data_coleta'              => 'required',
            'tanque_tipo_id'           => 'required',
            'coleta_parametro_tipo_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'data_coleta.required'              => 'O campo "Data da coleta" deve ser informado.',
            'tanque_tipo_id.required'           => 'O campo "Tipo de tanque" deve ser informado.',
            'coleta_parametro_tipo_id.required' => 'O campo "Parâmetro" deve ser informado.',
        ];
    }
}
