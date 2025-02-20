<?php

namespace App\Service\Master\General\Company;

use App\Models\Company;

class CompanyRepository
{
    public function getCompanies()
    {
        return Company::select('id', 'code', 'name');
    }

    public function selectedCompany(int $companyId)
    {
        return Company::where('id', $companyId)->first();
    }
}
