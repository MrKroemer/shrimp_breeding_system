<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArracoamentosCreateFormRequest extends FormRequest
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
            'data_aplicacao' => 'required',
            'setor_id'       => 'required',
        ];
    }

    public function messages()
    {
        return [
            'data_aplicacao.required' => 'O campo "Data da aplicação" deve ser informado.',
            'setor_id.required'       => 'O campo "Setores" deve ser informado.',
        ];
    }
}
