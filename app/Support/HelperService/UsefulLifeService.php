<?php

namespace App\Support\HelperService;

class UsefulLifeService
{
    public static function getUsefulLife($code, $goodsMaterial): ?int
    {

        if ($code === '121') {
            return null;
        }

        if ($code === '122') {
            return 20;
        }

        if ($code === '123' || $code === '124') {
            return 8;
        }

        if ($code === '125' && $goodsMaterial === 'Besi' || $code === '126' && $goodsMaterial === 'Besi') {
            return 8;
        }

        if ($code === '125' && $goodsMaterial === 'Non besi' || $code === '126' && $goodsMaterial === 'Non besi') {
            return 4;
        }


        return null;
    }
}
