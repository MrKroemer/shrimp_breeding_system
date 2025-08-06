<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CertificacaoReprodutoresCreateFormRequest extends FormRequest
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
            'numero_certificacao'  => 'required',
            'plantel'              => 'required',
            'familia'              => 'required',
            'data_estresse'        => 'required',
            'data_coleta'          => 'required',
            'tanque_origem_id'     => 'required',
        ];
    }

    public function messages()
    {
        return [
            'numero_certificacao.required'  => 'O campo "Número Certificação" deve ser informado.',
            'plantel.required'              => 'O campo "Plantel" deve ser informado.',
            'familia.required'              => 'O campo "Familia" deve ser informado.',
            'data_estresse.required'        => 'O campo "Data Estresse" deve ser informado.',
            'data_coleta.required'          => 'O campo "Data Coleta" deve ser informado.',
            'tanque_origem_id.required'     => 'O campo "Tanque de Origem" deve ser selecionado.',
        ];
    }
}
