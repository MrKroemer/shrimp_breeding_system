<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferenciasCreateFormRequest extends FormRequest
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
            'ciclo_origem_id'             => 'required',
            'ciclo_destino_id'            => 'required',
            'quantidade'                  => 'required|integer',
            'data_movimento'              => 'required',
        ];
    }

    public function messages()
    {
        return [
            'ciclo_origem.required'           => 'O campo "Ciclo de Origem deve ser selecionado" deve ser informado.',
            
            'ciclo_destino.required'          => 'O campo "Ciclo de Destino" deve ser informado.',
            
            'quantidade.required'             => 'O campo "Quantidade Transferida" deve ser informado.',

            'data_movimento.required'         => 'O campo "Data Transferência" deve ser informado.',
        ];
    }
}
