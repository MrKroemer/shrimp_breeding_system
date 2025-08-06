<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EspeciesCreateFormRequest extends FormRequest
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
            'nome_cientifico' => 'required|max:100',
        ];
    }

    public function messages()
    {
        return [
            'nome_cientifico.required' => 'O campo "Nome científico" deve ser informado.',
            'nome_cientifico.max'      => 'O campo "Nome científico" deve possuir no máximo :max caracteres.',
        ];
    }
}
