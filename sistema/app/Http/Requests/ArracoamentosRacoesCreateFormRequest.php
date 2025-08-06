<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArracoamentosRacoesCreateFormRequest extends FormRequest
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
            'racoes_porcentagem' => 'required',
            'racoes_produto'     => 'required',
        ];
    }

    public function messages()
    {
        return [
            'racoes_porcentagem.required' => 'O campo "Porcentagem" deve ser informado.',
            'racoes_produto.required'     => 'O campo "Ração" deve ser informado.',
        ];
    }
}
