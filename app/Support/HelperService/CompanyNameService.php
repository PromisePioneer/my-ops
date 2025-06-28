<?php

namespace App\Support\HelperService;

class CompanyNameService
{
    public function convertCompanyNameToCapitalLetter(string $companyName): string
    {
        $companyName = strtolower($companyName);


        $convertCompanyNameToArray = explode(" ", $companyName);

        $newString = '';
        $newPTKey = '';

        if (($key = array_search('pt.' || 'pt', $convertCompanyNameToArray)) !== false) {
            $newPTKey = $convertCompanyNameToArray[$key];
            unset($convertCompanyNameToArray[$key]);
        }

        foreach ($convertCompanyNameToArray as $abbr) {
            $newString .= strtolower($abbr) . ' ';
        }

        return strtoupper($newPTKey) . ' ' . ucwords(trim($newString));
    }


}
