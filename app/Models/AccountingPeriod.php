<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountingPeriod extends Model
{
    protected $table = 'accounting_periods';
    protected $fillable = [
        'year',
    ];
}
