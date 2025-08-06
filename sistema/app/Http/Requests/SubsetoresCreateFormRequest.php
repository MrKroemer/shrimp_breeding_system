<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubsetoresCreateFormRequest extends FormRequest
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
            'nome'  => 'required|max:50',
            'sigla' => 'max:50',
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O campo "Nome" deve ser informado.',
            'nome.max'      => 'O campo "Nome" deve possuir no máximo :max caracteres.',

            'sigla.max'     => 'O campo "Sigla" deve possuir no máximo :max caracteres.',
        ];
    }
}
