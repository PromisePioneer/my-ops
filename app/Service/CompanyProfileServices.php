<?php

namespace App\Service;

use App\Models\CompanyProfile;

class CompanyProfileServices
{
    public function getCompanyProfile()
    {
        return CompanyProfile::where('id', 1)->first();
    }
}
