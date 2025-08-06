<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecriacaoPeixesFormRequest extends FormRequest
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
            'tanque_id'   => 'required',
            'codigo'      => 'unique:recriacao_peixes,codigo',
        ];
    }

    public function messages()
    {
        return [
            'data_inicio.required' => 'O campo "Data de início" deve ser informado.',
            
            'tanque_id.required'   => 'O campo "Tanque" deve ser informado.',
            
            'codigo.unique'        => 'Já existe uma recriação registrada com esse código.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'codigo' => mb_strtoupper($this->codigo),
        ]);
    }
}
