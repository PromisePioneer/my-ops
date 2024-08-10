<?php

namespace App\Helper;

function convertToRoman(int $number): string
{
    $integer = intval($number);
    $result = '';

    $lookup = [
        'M' => 1000,
        'CM' => 900,
        'D' => 500,
        'CD' => 400,
        'C' => 100,
        'XC' => 90,
        'L' => 50,
        'XL' => 40,
        'X' => 10,
        'IX' => 9,
        'V' => 5,
        'IV' => 4,
        'I' => 1,
    ];

    foreach ($lookup as $roman => $value) {
        $matches = intval($number / $value);
        $result .= str_repeat($roman, $matches);
        $number = $number % $value;
    }

    return $result;
}
