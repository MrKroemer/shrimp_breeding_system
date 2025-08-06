<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArracoamentosAplicacoesTiposCreateFormRequest extends FormRequest
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
            'nome' => 'required|max:50|unique:arracoamentos_aplicacoes_tipos,nome',
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O campo "Nome" deve ser informado.',
            'nome.max'      => 'O campo "Nome" deve possuir no máximo :max caracteres.',
            'nome.unique'   => 'Esse tipo de aplicação já existe.',
        ];
    }
}
