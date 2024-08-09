<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $npwp
 * @property string $bank
 * @property string $bank_account_number
 * @property string $bank_account_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyProfile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyProfile whereBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyProfile whereBankAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyProfile whereBankAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyProfile whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyProfile whereNpwp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyProfile whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
