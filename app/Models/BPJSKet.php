<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BPJSKet extends Model
{
    use HasFactory;

    protected $table = 'bpjs_ket_rate';
    protected $fillable = [
        'name',
        'company_rate',
        'employee_rate',
    ];
}
