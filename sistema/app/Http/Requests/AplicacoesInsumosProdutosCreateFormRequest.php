<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AplicacoesInsumosProdutosCreateFormRequest extends FormRequest
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
            'produto_id' => 'required|numeric',
            'quantidade' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'produto_id.required' => 'O campo "Produto" deve ser informado.',
            'produto_id.numeric'  => 'O campo "Produto" deve ser numérico.',
            
            'quantidade.required' => 'O campo "Quantidade" deve ser informado.',
            'quantidade.numeric'  => 'O campo "Quantidade" deve ser numérico.',
        ];
    }
}
