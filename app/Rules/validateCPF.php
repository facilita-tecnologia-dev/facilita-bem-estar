<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class validateCPF implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^\d{3}\.\d{3}\.\d{3}-\d{2}$/', $value)) {
            $fail('O CPF deve estar no formato 000.000.000-00.');

            return;
        }

        $cpf = preg_replace('/[.\-]/', '', $value);

        if (strlen($cpf) !== 11) {
            $fail('O CPF informado é inválido.');

            return;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $fail('O CPF informado é inválido.');

            return;
        }

        for ($t = 9; $t < 11; $t++) {
            $sum = 0;
            for ($i = 0; $i < $t; $i++) {
                $sum += (int) $cpf[$i] * (($t + 1) - $i); // 👈 cast para int
            }
            $d = ((10 * $sum) % 11) % 10;
            if ((int) $cpf[$t] !== $d) {
                $fail('O CPF informado é inválido.');

                return;
            }
        }
    }
}
