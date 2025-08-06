<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArracoamentosReferenciaisCreateFormRequest extends FormRequest
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
            'dias_cultivo' => 'numeric',
            'peso_medio'   => 'numeric',
            'porcentagem'  => 'numeric',
            'crescimento'  => 'numeric',
        ];
    }

    public function messages()
    {
        return [
            'dias_cultivo.numeric' => 'O campo "Dias de cultivo" deve ser numérico.',
            'peso_medio.numeric'   => 'O campo "Peso médio" deve ser numérico.',
            'porcentagem.numeric'  => 'O campo "Porcentagem baseada na biomassa" deve ser numérico.',
            'crescimento.numeric'  => 'O campo "Crescimento diário" deve ser numérico.',
        ];
    }
}
