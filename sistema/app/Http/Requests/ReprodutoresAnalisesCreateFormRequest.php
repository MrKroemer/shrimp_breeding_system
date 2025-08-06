<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReprodutoresAnalisesCreateFormRequest extends FormRequest
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
            'sigla'                        => 'required',
            'descricao'                    => 'required',
            
        ];
    }

    public function messages()
    {
        return [
            'sigla'                        => 'O campo "Sigla" deve ser informado',
            'descricao'                    => 'O campo "Descricao" deve ser informado',
        ];
    }
}
