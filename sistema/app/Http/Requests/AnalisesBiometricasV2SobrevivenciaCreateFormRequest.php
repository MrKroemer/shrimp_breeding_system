<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalisesBiometricasV2SobrevivenciaCreateFormRequest extends FormRequest
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
            'sobrevivencia'      => 'required',
        ];
    }

    public function messages()
    {
        return [
           
            'sobrevivencia.required' => 'O campo "Percentual de sobrevivência" deve ser informado.',

        ];
    }
}
