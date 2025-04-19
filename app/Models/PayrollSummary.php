<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollSummary extends Model
{
    protected $table = 'payroll_summary';
    protected $fillable = [
        'user_id',
        'payroll_period',
        'sick_count',
        'permission_count',
        'leave_count',
        'not_checkout_count',
        'not_checkin_count',
        'basic_salary',
        'position_allowance',
        'overtime_allowance',
        'transportation_allowance',
        'meal_allowance',
        'thr_allowance',
        'sla_deduction',
        'nine_past_fifteen_deduction',
        'additional_deduction',
        'marketing_bonus',
        'sales_bonus',
        'bonus_project',
        'additional_bonus',
        'total_allowance',
        'total_deduction',
        'total_bonus'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
