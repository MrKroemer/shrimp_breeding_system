<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LotesPeixesOvosFormRequest extends FormRequest
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
            'qtd_ovos'          => 'required',
            'qtd_femeas'        => 'required',
            'tanque_origem_id'  => 'required',
            'tanque_destino_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'qtd_ovos.required'          => 'O campo "Qtd. de ovos" deve ser informado.',
            
            'qtd_femeas.required'        => 'O campo "Qtd. de fêmeas" deve ser informado.',
            
            'tanque_origem_id.required'  => 'O campo "Tanque de origem" deve ser informado.',

            'tanque_destino_id.required' => 'O campo "Tanque de destino" deve ser informado.',
        ];
    }
}
