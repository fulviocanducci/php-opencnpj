<?php

namespace Canducci\OpenCnpj\Utils;

final class Valid {
    public static function isCnpj(string $cnpj): bool
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);
        if (strlen($cnpj) !== 14) {
            return false;
        }
        if (preg_match('/^(.)\1*$/', $cnpj)) {
            return false;
        }
        $lengths = [5,6];
        for ($t = 12; $t < 14; $t++) {
            $sum = 0;
            $pos = 0;
            for ($i = 0; $i < $t; $i++) {
                $sum += $cnpj[$i] * $lengths[$pos % 2];
                $pos++;
            }
            $digit = ($sum % 11 < 2) ? 0 : 11 - ($sum % 11);
            if ($cnpj[$t] != $digit) {
                return false;
            }
            array_unshift($lengths, $lengths[0] + 1);
            array_pop($lengths);
        }
        return true;
    }
}