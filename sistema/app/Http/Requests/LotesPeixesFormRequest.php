<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LotesPeixesFormRequest extends FormRequest
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
            'codigo'      => 'unique:lotes_peixes,codigo',
            'data_inicio' => 'required',
            'especie_id'  => 'required',
        ];
    }

    public function messages()
    {
        return [
            'codigo.unique'        => 'Já existe um lote registrado com esse código.',

            'data_inicio.required' => 'O campo "Data de início" deve ser informado.',

            'especie_id.required'  => 'O campo "Espécie" deve ser informado.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'codigo' => mb_strtoupper($this->codigo),
        ]);
    }
}
