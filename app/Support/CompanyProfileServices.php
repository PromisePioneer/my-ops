<?php

namespace App\Support;

use App\Models\CompanyProfile;

class CompanyProfileServices
{
    public function getCompanyProfile()
    {
        return CompanyProfile::where('id', 1)->first();
    }
}
