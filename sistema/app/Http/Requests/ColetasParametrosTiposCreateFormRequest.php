<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColetasParametrosTiposCreateFormRequest extends FormRequest
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
            'sigla'                        => 'required',
            'minimo'                       => 'required',
            'maximo'                       => 'required',
            'minimoy'                      => 'required',
            'maximoy'                      => 'required',
            'intervalo'                    => 'required',
            'unidade_medida_id'            => 'required',
        ];
    }

    public function messages()
    {
        return [
            'sigla'                        => 'O campo "Sigla" deve ser informado',
            'minimo'                       => 'O campo "Minimo" deve ser informado',
            'maximo'                       => 'O campo "Maximo" deve ser informado',
            'minimoy'                      => 'O campo "Minimo do Gráfico" deve ser informado',
            'maximoy'                      => 'O campo "Maximo do Gráfico" deve ser informado',
            'intervalo'                    => 'O campo "Intervalo do Gráfico" deve ser informado',
            'unidade_medida_id'            => 'O campo "Unidade de Medida" deve ser informado',
        ];
    }
}
