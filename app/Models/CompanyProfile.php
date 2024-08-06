<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    protected $table = 'company_profile';

    protected $fillable = [
        'name',
        'address',
        'npwp',
        'bank',
        'bank_account_number',
        'bank_account_name',
    ];
}
