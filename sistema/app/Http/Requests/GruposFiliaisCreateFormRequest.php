<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GruposFiliaisCreateFormRequest extends FormRequest
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
            'filial_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'filial_id.required' => 'O campo "Filial" deve ser informado.',
        ];
    }
}
