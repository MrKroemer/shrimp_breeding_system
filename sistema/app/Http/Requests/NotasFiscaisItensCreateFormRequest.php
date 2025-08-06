<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotasFiscaisItensCreateFormRequest extends FormRequest
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
            'quantidade'     => 'required|numeric',
            'valor_unitario' => 'required|numeric',
            'produto_id'     => 'required',
        ];
    }

    public function messages()
    {
        return [
            'quantidade.required'    => 'O campo "Quantidade" deve ser informado.',
            'quantidade.numeric'     => 'O campo "Quantidade" deve ser numérico.',

            'valor_unitario.required'    => 'O campo "Valor unitário" deve ser informado.',
            'valor_unitario.numeric'     => 'O campo "Valor unitário" deve ser numérico.',

            'produto_id.required'     => 'O campo "Produto" deve ser informado.',
        ];
    }
}
