<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PovoamentosCreateFormRequest extends FormRequest
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
            'ciclo_id'    => 'required',
        ];
    }

    public function messages()
    {
        return [
            'data_inicio.required' => 'O campo "Data de início" deve ser informado.',

            'ciclo_id.required'    => 'O campo "Ciclo" deve ser informado.',
        ];
    }
}
