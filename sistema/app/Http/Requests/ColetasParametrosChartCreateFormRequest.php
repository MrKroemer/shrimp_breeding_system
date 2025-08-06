<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColetasParametrosChartCreateFormRequest extends FormRequest
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
            'data_inicial' => 'required',
            'data_final'   => 'required',
            'parametros'   => 'required',
            'tanques'      => 'required',
        ];
    }

    public function messages()
    {
        return [
            'data_inicial.required' => 'Informe uma data para o início do período de consulta.',
            'data_final.required'   => 'Informe uma data para o final do período de consulta.',
            'parametros.required'   => 'Informe um ou mais parâmetros para gerar os gráficos.',
            'tanques.required'      => 'Informe um ou mais tanques para gerar os gráficos.',
        ];
    }
}
