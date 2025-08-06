<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Usuarios;

class UserInformationValidateRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $usuario = Usuarios::where($attribute, trim($value))->get();

        return $usuario->isNotEmpty();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Não conseguimos encontrar nenhum usuário que possui essa informação.';
    }
}
