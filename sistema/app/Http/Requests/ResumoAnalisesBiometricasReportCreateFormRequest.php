<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResumoAnalisesBiometricasReportCreateFormRequest  extends FormRequest
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
            'data_solicitacao' => 'required'
            /*'data_inicial' => 'required',
            'data_final'   => 'required',*/
        ];
    }

    public function messages()
    {
        return [
            'data_solicitacao.required' => 'Informe uma data para a consulta.'
            /*'data_inicial.required' => 'Informe uma data inicial para consulta',
            'data_final.required'   => 'Informe uma data final para consulta',*/
        ];
    }
}
