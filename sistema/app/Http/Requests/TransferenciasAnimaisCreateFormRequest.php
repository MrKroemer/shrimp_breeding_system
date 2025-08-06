<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferenciasAnimaisCreateFormRequest extends FormRequest
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
            'quantidade'       => 'required',
            'data_movimento'   => 'required',
            'ciclo_origem_id'  => 'required',
            'ciclo_destino_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'quantidade.required'       => 'O campo "Quantidade à transferir" deve ser informado.',
            'data_movimento.required'   => 'O campo "Data da transferência" deve ser informado.',
            'ciclo_origem_id.required'  => 'O campo "Ciclo de origem" deve ser selecionado.',
            'ciclo_destino_id.required' => 'O campo "Ciclo de destino" deve ser informado.',
        ];
    }
}
