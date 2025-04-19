<?php

namespace App\Helper;

use Carbon\Carbon;

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

function parse($configString): array
{
    $configArray = [];
    $lines = explode("\r\n", trim($configString));

    foreach ($lines as $line) {
        [$key, $value] = explode('=', $line, 2);
        $configArray[trim($key)] = trim($value);
    }

    return $configArray;
}


function formatDate($date): string
{
    return Carbon::parse($date)->locale('id')
        ->settings(['formatFunction' => 'translatedFormat'])
        ->format('j F Y');
}


function randomDigits(): string
{
    $result = '';

    for ($i = 0; $i < 3; $i++) {
        $result .= random_int(0, 9);
    }

    return $result;
}
