<?php

namespace Canducci\OpenCnpj\Utils;

final class Valid
{
    public static function isCnpj(string $cnpj): bool
    {
        $cnpj = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $cnpj));

        if (strlen($cnpj) !== 14 || !ctype_alnum($cnpj)) {
            return false;
        }

        if (preg_match('/^(.)\1*$/', $cnpj)) {
            return false;
        }

        $digits = substr($cnpj, 0, 12);
        $weights1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $weights2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        $sum = 0;

        for ($i = 0; $i < 12; $i++) {
            $sum += (ord($digits[$i]) - 48) * $weights1[$i];
        }

        $digit1 = ($sum % 11 < 2) ? 0 : 11 - ($sum % 11);
        $digits .= $digit1;
        $sum = 0;

        for ($i = 0; $i < 13; $i++) {
            $sum += (ord($digits[$i]) - 48) * $weights2[$i];
        }

        $digit2 = ($sum % 11 < 2) ? 0 : 11 - ($sum % 11);

        return (int)$cnpj[12] === $digit1 && (int)$cnpj[13] === $digit2;
    }
}

// final class Valid
// {
//     public static function isCnpj(string $cnpj): bool
//     {
//         $cnpj = preg_replace('/\D/', '', $cnpj);
//         if (strlen($cnpj) !== 14) {
//             return false;
//         }
//         if (preg_match('/^(.)\1*$/', $cnpj)) {
//             return false;
//         }
//         $digits = substr($cnpj, 0, 12);
//         $weights1 = [5,4,3,2,9,8,7,6,5,4,3,2];
//         $weights2 = [6,5,4,3,2,9,8,7,6,5,4,3,2];
//         $sum = 0;
//         for ($i = 0; $i < 12; $i++) {
//             $sum += (int)$digits[$i] * $weights1[$i];
//         }
//         $digit1 = ($sum % 11 < 2) ? 0 : 11 - ($sum % 11);
//         $digits .= $digit1;
//         $sum = 0;
//         for ($i = 0; $i < 13; $i++) {
//             $sum += (int)$digits[$i] * $weights2[$i];
//         }
//         $digit2 = ($sum % 11 < 2) ? 0 : 11 - ($sum % 11);

//         return $cnpj[12] == $digit1 && $cnpj[13] == $digit2;
//     }
// }