<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArracoamentosAlimentacoesCreateFormRequest extends FormRequest
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
            'quantidades_porcentagem' => 'required',
            'quantidades_primeira'    => 'required',
            'quantidades_ultima'      => 'required',
        ];
    }

    public function messages()
    {
        return [
            'quantidades_porcentagem.required' => 'O campo "Porcentagem" deve ser informado.',
            'quantidades_primeira.required'    => 'O campo "Alimentação inicial" deve ser informado.',
            'quantidades_ultima.required'      => 'O campo "Alimentação final" deve ser informado.',
        ];
    }
}
