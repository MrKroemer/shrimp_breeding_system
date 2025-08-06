<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreparacoesAplicacoesEditFormRequest extends FormRequest
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
            'preparacao_tipo_id' => 'required',
            'produto_id'         => 'required',
            'quantidade'         => 'numeric',
        ];
    }
    
    public function messages()
    {
        return [
            'preparacao_tipo_id.required' => 'O campo "Tipo de preparação" deve ser informado.',

            'produto_id.required'         => 'O campo "Produto" deve ser informado.',
            
            'quantidade.numeric'          => 'O campo "Quantidade" é obrigatório e deve ser numérico.',
        ];
    }
}
