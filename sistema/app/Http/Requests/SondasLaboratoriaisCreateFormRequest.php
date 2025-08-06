<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SondasLaboratoriaisCreateFormRequest extends FormRequest
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
            'nome'         => 'required',
            'marca'        => 'required',
            'numero_serie' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nome.required'         => 'Para cadastrar uma nova sonda, um nome deve informado.',
            'marca.required'        => 'Para cadastrar uma nova sonda, uma marca deve informado.',
            'numero_serie.required' => 'Para cadastrar uma nova sonda, um Nº de série deve informado.',
        ];
    }
}
