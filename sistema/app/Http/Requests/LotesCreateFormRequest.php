<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LotesCreateFormRequest extends FormRequest
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
            'estoque_entrada_id' => 'required',
            'especie_id'      => 'required',
            'genetica'        => 'required',
            'quantidade'      => 'required|numeric',
            'classe_idade'    => 'required',
            'data_saida'      => 'required',
            'data_entrada'    => 'required',
        ];
    }

    public function messages()
    {
        return [
            'estoque_entrada_id.required' => 'O campo "Pós-larvas em estoque" deve ser informado.',

            'especie_id.required'      => 'O campo "Espécie" deve ser informado.',

            'genetica.required'        => 'O campo "Genética" deve ser informado',

            'quantidade.required'      => 'O campo "Quantidade" deve ser informado.',
            'quantidade.numeric'       => 'O campo "Quantidade" deve ser numérico.',

            'classe_idade.required'    => 'O campo "Classe/Idade" deve ser informado.',

            'data_saida.required'      => 'O campo "Data de saída" deve ser informado.',

            'data_entrada.required'    => 'O campo "Data de entrada" deve ser informado.',
        ];
    }
}
