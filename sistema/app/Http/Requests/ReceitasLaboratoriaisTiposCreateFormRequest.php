<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceitasLaboratoriaisTiposCreateFormRequest extends FormRequest
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
            'tipo_nome' => 'required|max:50|unique:receitas_laboratoriais_tipos,nome',
        ];
    }

    public function messages()
    {
        return [
            'tipo_nome.required'  => 'Para criar um novo tipo de receita, um nome deve informado.',
            'tipo_nome.max'       => 'O nome do tipo de receita, deve possuir no máximo :max caracteres.',
            'tipo_nome.unique'    => 'Esse tipo de receita já existe.',
        ];
    }
}
