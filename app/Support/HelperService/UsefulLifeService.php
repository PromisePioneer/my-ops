<?php

namespace App\Support\HelperService;

class UsefulLifeService
{
    public static function getUsefulLife($code, $categoryName, $buildingType): ?int
    {

        if ($code === '121') {
            return null;
        }

        // bangunan
        if ($code === '122' && $buildingType === 'Permanen') {
            return 20;
        }

        // bangunan
        if ($code === '122' && $buildingType === 'Tidak Permanen') {
            return 10;
        }

        // kategori 1
        if (
            ($code === '123' || $code === '124' || $code === '125' || $code === '126' || $code === '127')
            && ($categoryName === 'Kategori 1')
        ) {
            return 4;
        }

        //kategori 2
        if (
            ($code === '123' || $code === '124' || $code === '125' || $code === '126' || $code === '127')
            && ($categoryName === 'Kategori 2')
        ) {
            return 8;
        }

        //kategori 3
        if (
            ($code === '123' || $code === '124' || $code === '125' || $code === '126' || $code === '127')
            && ($categoryName === 'Kategori 2')
        ) {
            return 16;
        }

        //kategori 4
        if (
            ($code === '123' || $code === '124' || $code === '125' || $code === '126' || $code === '127')
            && ($categoryName === 'Kategori 2')
        ) {
            return 20;
        }
        return null;
    }
}
