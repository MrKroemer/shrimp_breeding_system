<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreparacoesCreateFormRequest extends FormRequest
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
            'ciclo_id'    => 'required',
            'data_inicio' => 'required',
        ];
    }
    
    public function messages()
    {
        return [
            'ciclo_id.required'    => 'O campo "Ciclo" deve ser informado.',
            'data_inicio.required' => 'O campo "Início da preparação" deve ser informado.',
        ];
    }
}
