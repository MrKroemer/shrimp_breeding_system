<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArracoamentosAplicacoesCreateFormRequest extends FormRequest
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
            'arracoamento_aplicacao_tipo_id' => 'required',
            'arracoamento_aplicacao_item_id' => 'required',
            'quantidade'                     => 'required',
        ];
    }

    public function messages()
    {
        return [
            'arracoamento_aplicacao_tipo_id.required' => 'O campo "Aplicação de" deve ser informado.',
            'arracoamento_aplicacao_item_id.required' => 'O campo "Item" deve ser informado.',
            'quantidade.required'                     => 'O campo "Quantidade" deve ser informado.',
        ];
    }
}
