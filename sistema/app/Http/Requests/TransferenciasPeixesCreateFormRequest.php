<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferenciasPeixesCreateFormRequest extends FormRequest
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
            'data_movimento' => 'required',
            'lote_peixes_id' => 'required',
            'tanque_id'      => 'required',
            'quantidade'     => 'required',
        ];
    }

    public function messages()
    {
        return [
            'data_movimento.required' => 'O campo "Data da transferência" deve ser informado.',

            'lote_peixes_id.required' => 'O campo "Lote" deve ser selecionado.',

            'tanque_id.required'      => 'O campo "Tanque" deve ser informado.',
            
            'quantidade.required'     => 'O campo "Quantidade à transferir" deve ser informado.',
        ];
    }
}
