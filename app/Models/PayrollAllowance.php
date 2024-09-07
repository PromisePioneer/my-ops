<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollAllowance extends Model
{
    use HasFactory;

    protected $table = 'payroll_allowances';
    protected $fillable = [
        'name',
        'amount',
    ];
}
