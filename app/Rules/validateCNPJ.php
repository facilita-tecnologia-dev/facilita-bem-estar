<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class validateCNPJ implements ValidationRule
{
    /**
     * Executa a validação do CNPJ.
     *
     * @param  string  $attribute Nome do atributo que está sendo validado.
     * @param  mixed   $value     Valor informado para o CNPJ.
     * @param  \Closure  $fail   Função callback para reportar a falha na validação.
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cnpj = preg_replace('/\D/', '', $value);

        if (strlen($cnpj) !== 14) {
            $fail('O CNPJ informado é inválido.');
            return;
        }

        if (preg_match('/^(.)\1*$/', $cnpj)) {
            $fail('O CNPJ informado é inválido.');
            return;
        }

        $calcDigit = function (string $cnpjPart, array $weights): int {
            $sum = 0;
            foreach ($weights as $index => $weight) {
                $sum += $cnpjPart[$index] * $weight;
            }
            $remainder = $sum % 11;
            return ($remainder < 2) ? 0 : 11 - $remainder;
        };

        $weightsFirst = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $firstDigit = $calcDigit(substr($cnpj, 0, 12), $weightsFirst);

        if ((int) $cnpj[12] !== $firstDigit) {
            $fail('O CNPJ informado é inválido.');
            return;
        }

        $weightsSecond = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $secondDigit = $calcDigit(substr($cnpj, 0, 13), $weightsSecond);

        if ((int) $cnpj[13] !== $secondDigit) {
            $fail('O CNPJ informado é inválido.');
            return;
        }
    }
}