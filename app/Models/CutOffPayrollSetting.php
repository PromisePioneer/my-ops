<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutOffPayrollSetting extends Model
{

    use HasFactory;

    protected $table = 'cut_off_payroll_settings';
    protected $fillable = [
        'attendance_period_start',
        'attendance_period_end',
        'emp_payroll_period_start',
        'emp_payroll_period_end',
    ];
}
