<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AplicacoesInsumosReceitasCreateFormRequest extends FormRequest
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
            'receita_laboratorial_id' => 'required|numeric',
            'quantidade' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'receita_laboratorial_id.required' => 'O campo "Receita" deve ser informado.',
            'receita_laboratorial_id.numeric'  => 'O campo "Receita" deve ser numérico.',

            'quantidade.required' => 'O campo "Quantidade" deve ser informado.',
            'quantidade.numeric'  => 'O campo "Quantidade" deve ser numérico.',
        ];
    }
}
