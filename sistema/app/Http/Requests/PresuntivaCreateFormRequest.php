<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PresuntivaCreateFormRequest extends FormRequest
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
        'ciclo_id'     => 'required', 
        'data_analise' => 'required'     
        ];
    }

    public function messages()
    {
        return [
        'data.required'                               => 'O campo "Data" deve ser informado.',  
        'ciclo_id.required'                           => 'O campo "Ciclo" deve ser selecionado.'
        ];
    }
}
