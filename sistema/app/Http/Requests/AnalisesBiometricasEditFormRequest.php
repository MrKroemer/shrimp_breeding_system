<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalisesBiometricasEditFormRequest extends FormRequest
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
            'sobrevivencia'  => 'numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            'sobrevivencia.numeric' => 'O campo "Percentual de sobrevivência" deve ser informado.',
            'sobrevivencia.min'     => 'O campo "Percentual de sobrevivência" deve ser maior que zero.',
        ];
    }
}
