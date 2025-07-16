<?php

namespace App\Support\Master\Common\Company;

use AllowDynamicProperties;
use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;

#[AllowDynamicProperties] class CompanyRepository
{

    public function __construct()
    {
        $this->company = new Company();
    }

    public function getCompanies(): Builder|Company
    {
        return $this->company->query();
    }

    public function selectedCompany(int $companyId): Company
    {
        return $this->company->where('id', $companyId)->first();
    }
}
