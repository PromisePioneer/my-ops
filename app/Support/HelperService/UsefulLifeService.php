<?php

namespace App\Support\HelperService;

class UsefulLifeService
{
    public static function getUsefulLife($code, $nonBuildingGroup, $buildingType): ?int
    {
        if ($code === '121') {
            return null;
        }

        // bangunan tidak permanen
        if ($code === '122' && $buildingType === 'Tidak Permanen') {
            return 10;
        }

        // bangunan permanen
        if ($code === '122' && $buildingType === 'Permanen') {
            return 20;
        }


        // Kelompok 1
        if (
            ($code === '123' || $code === '124' || $code === '125' || $code === '126' || $code === '127')
            && ($nonBuildingGroup === 'Kelompok I')
        ) {
            return 4;
        }

        //Kelompok 2
        if (
            ($code === '123' || $code === '124' || $code === '125' || $code === '126' || $code === '127')
            && ($nonBuildingGroup === 'Kelompok II')
        ) {
            return 8;
        }

        //Kelompok 3
        if (
            ($code === '123' || $code === '124' || $code === '125' || $code === '126' || $code === '127')
            && ($nonBuildingGroup === 'Kelompok III')
        ) {
            return 16;
        }

        //Kelompok 4
        if (
            ($code === '123' || $code === '124' || $code === '125' || $code === '126' || $code === '127')
            && ($nonBuildingGroup === 'Kelompok IV')
        ) {
            return 20;
        }
        return null;
    }
}
