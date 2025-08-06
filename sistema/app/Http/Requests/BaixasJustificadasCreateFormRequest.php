<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaixasJustificadasCreateFormRequest extends FormRequest
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
            'data_movimento' => 'required',
            // 'descricao'      => 'required',
        ];
    }

    public function messages()
    {
        return [
            'data_movimento.required' => 'O campo "Data do movimento" deve ser informado.',
            // 'descricao.required'      => 'O campo "Justificativa da baixa" deve ser informado.',
        ];
    }
}
