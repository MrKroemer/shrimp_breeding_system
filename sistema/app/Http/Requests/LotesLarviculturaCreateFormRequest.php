<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LotesLarviculturaCreateFormRequest extends FormRequest
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
            'lote'           => 'required',
            'quantidade'     => 'required|numeric',
            'sobrevivencia'  => 'required|numeric',
            'cv'             => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'lote.required'           => 'O campo "Lote" deve ser informado.',
            'quantidade.required'     => 'O campo "Quantidade" deve ser informado.',
            'sobrevivencia.required'  => 'O campo "Sobrevivencia" deve ser informado.',
            'cv.required'             => 'O campo "Coeficiente de Variação" deve ser informado.',

        ];
    }
}
