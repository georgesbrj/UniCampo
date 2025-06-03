<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CpfOuCnpj implements Rule
{
    public function passes($attribute, $value)
    {
        $value = preg_replace('/\D/', '', $value);

        if (strlen($value) === 11) {
            return $this->validarCpf($value);
        }

        if (strlen($value) === 14) {
            return $this->validarCnpj($value);
        }

        return false;
    }

    public function message()
    {
        return 'O campo :attribute não é um CPF ou CNPJ válido.';
    }

    private function validarCpf($cpf)
    {
        if (preg_match('/(\d)\1{10}/', $cpf)) return false;

        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$t] != $d) return false;
        }

        return true;
    }

    private function validarCnpj($cnpj)
    {
        if (preg_match('/(\d)\1{13}/', $cnpj)) return false;

        $tamanho = strlen($cnpj) - 2;
        $numeros = substr($cnpj, 0, $tamanho);
        $digitos = substr($cnpj, $tamanho);
        $soma = 0;
        $pos = $tamanho - 7;

        for ($i = $tamanho; $i >= 1; $i--) {
            $soma += $numeros[$tamanho - $i] * $pos--;
            if ($pos < 2) $pos = 9;
        }

        $resultado = ($soma % 11 < 2) ? 0 : 11 - ($soma % 11);
        if ($resultado != $digitos[0]) return false;

        $tamanho += 1;
        $numeros = substr($cnpj, 0, $tamanho);
        $soma = 0;
        $pos = $tamanho - 7;

        for ($i = $tamanho; $i >= 1; $i--) {
            $soma += $numeros[$tamanho - $i] * $pos--;
            if ($pos < 2) $pos = 9;
        }

        $resultado = ($soma % 11 < 2) ? 0 : 11 - ($soma % 11);
        return $resultado == $digitos[1];
    }
}
