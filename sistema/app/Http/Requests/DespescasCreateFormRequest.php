<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DespescasCreateFormRequest extends FormRequest
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
            'ciclo_id'            => 'required',
            'data_inicio'         => 'required',
            'qtd_prevista'        => 'numeric',
            'qtd_despescada'      => 'numeric',
            'peso_medio'          => 'numeric',
            'tipo_despesca'       => 'required',
        ];
    }

    public function messages()
    {
        return [
            'ciclo_id.required'            => 'O campo "Ciclo" deve ser informado.',
            
            'data_inicio.required'         => 'O campo "Data de início" deve ser informado.',
            
            'qtd_prevista.numeric'         => 'O campo "Quantidade prevista" é obrigatório e deve ser numérico.',
            
            'qtd_despescada.numeric'       => 'O campo "Quantidade despescada" é obrigatório e deve ser numérico.',
            
            'peso_medio.numeric'           => 'O campo "Peso médio" é obrigatório e deve ser numérico.',
            
            'tipo_despesca.required'       => 'O campo "Tipo de despesca" deve ser informado.',
        ];
    }
}
