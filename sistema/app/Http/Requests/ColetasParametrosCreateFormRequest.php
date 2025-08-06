<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColetasParametrosCreateFormRequest extends FormRequest
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
            'data_coleta'   => 'required',
            'setor_id'      => 'required',
            'coletas_parametros_tipos_id'  => 'required',
        ];
    }

    public function messages()
    {
        return [
            'data_coleta.required'   => 'O campo "Data" deve ser informado.',
            'setor_id.required'      => 'O campo "Setor" deve ser informado.',
            'coletas_parametros_tipos_id.required'  => 'O campo "Parâmetro" deve ser informado.',
        ];
    }
}
