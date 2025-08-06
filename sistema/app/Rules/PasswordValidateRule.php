<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PasswordValidateRule implements Rule
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
        return !(strlen($value) > 0 && strlen($value) < 8);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A "Senha" deve possuir no minimo 8 caracteres.';
    }
}
