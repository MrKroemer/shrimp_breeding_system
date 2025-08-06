<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SondasFatoresCreateFormRequest extends FormRequest
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
            'fator'                                 => 'required|numeric',
            'sonda_laboratorial_id'                 => 'required',
            'coleta_parametro_tipo_id'           => 'required',
        ];
    }

    public function messages()
    {
        return [
            'fator.required' => 'O campo "Fator" deve ser informado.',
            'sonda_laboratorial_id.required' => 'O campo "Sonda Laboratorial" deve ser informado.',
            'coleta_parametro_tipo_id.required'          => 'O campo "Parâmetro" deve ser informado.',
        ];
    }
}
