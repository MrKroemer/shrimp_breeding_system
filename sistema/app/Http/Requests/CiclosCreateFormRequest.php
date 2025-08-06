<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CiclosCreateFormRequest extends FormRequest
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
            'data_inicio' => 'required',
            'tipo'        => 'required',
            'tanque_id'   => 'required',
            'numero'      => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'data_inicio.required' => 'O campo "Data de início" deve ser informado.',
            
            'tipo.required'        => 'O campo "Tipo de cultivo" deve ser informado.',
            
            'tanque_id.required'   => 'O campo "Tanque" deve ser informado.',

            'numero.required'      => 'O campo "Nº do ciclo" deve ser informado.',
            'numero.integer'       => 'O campo "Nº do ciclo" deve ser um número inteiro.',
        ];
    }
}
