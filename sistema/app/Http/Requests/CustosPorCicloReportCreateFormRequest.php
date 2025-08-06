<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustosPorCicloReportCreateFormRequest extends FormRequest
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
            'data_solicitacao' => 'required',
            'ciclos'           => 'required',
        ];
    }

    public function messages()
    {
        return [
            'data_solicitacao.required' => 'Informe uma data para realizar a consulta.',
            'ciclos.required'           => 'Informe um ou mais tanques para gerar o relatório.',
        ];
    }
}
