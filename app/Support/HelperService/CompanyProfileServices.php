<?php

namespace App\Support\HelperService;

use App\Models\CompanyProfile;

class CompanyProfileServices
{
    public function getCompanyProfile()
    {
        return CompanyProfile::where('id', 1)->first();
    }
}
