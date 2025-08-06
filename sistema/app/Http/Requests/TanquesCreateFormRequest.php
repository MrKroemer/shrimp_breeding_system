<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TanquesCreateFormRequest extends FormRequest
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
            'nome'           => 'required|max:50',
            'sigla'          => 'required|max:20',
            'area'           => 'numeric',
            'altura'         => 'numeric',
            'volume'         => 'numeric',
            'nivel'          => 'numeric',
            'tanque_tipo_id' => 'required',
            'setor_id'       => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nome.required'  => 'O campo "Nome" deve ser informado.',
            'nome.max'       => 'O campo "Nome" deve possuir no máximo :max caracteres.',

            'sigla.required' => 'O campo "Sigla" deve ser informado.',
            'sigla.max'      => 'O campo "Sigla" deve possuir no máximo :max caracteres.',

            'area.numeric'   => 'O campo "Área" deve possuir apenas números.',
            'altura.numeric' => 'O campo "Altura" deve possuir apenas números.',
            'volume.numeric' => 'O campo "Volume" deve possuir apenas números.',
            'nivel.numeric'  => 'O campo "Nível" deve possuir apenas números.',

            'tanque_tipo_id.required'  => 'O campo "Tipo" deve ser informado.',
            'setor_id.required'        => 'O campo "Setor" deve ser informado.',
        ];
    }
}
