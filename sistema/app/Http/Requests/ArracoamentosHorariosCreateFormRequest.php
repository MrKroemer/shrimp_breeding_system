<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArracoamentosHorariosCreateFormRequest extends FormRequest
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
            'perfil'             => 'numeric',
            'racao_estimada_qtd' => 'numeric',
        ];
    }

    public function messages()
    {
        return [
            'perfil.numeric'             => 'Um perfil de arraçoamento deve ser selecionado.',
            'racao_estimada_qtd.numeric' => 'O campo "Ração estimada" deve ser informado corretamente.',
        ];
    }
}
