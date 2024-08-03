<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    protected $table = 'user_payroll';
    protected $fillable = [
        'user_id',
        'period_start',
        'period_end',
        'salary_date',
        'positional_allowance',
        'meal_allowance',
        'transportation_allowance',
        'overtime_allowance',
        'sales_bonus',
        'project_bonus',
        'other_bonus',
        'bpjs_tek_dues',
        'bpjs_kes_dues'
    ];

    // relationship
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    //querying data
    public function searchPayroll($request)
    {
        $search = $request->input('search');
        $payroll = Payroll::whereHas('user', static function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->orWhere('period_start', 'like', '%' . $search . '%')
            ->orWhere('period_end', 'like', '%' . $search . '%')
            ->orWhere('salary_date', 'like', '%' . $search . '%')
            ->orWhere('positional_allowance', 'like', '%' . $search . '%')
            ->orWhere('meal_allowance', 'like', '%' . $search . '%')
            ->orWhere('transportation_allowance', 'like', '%' . $search . '%')
            ->orWhere('overtime_allowance', 'like', '%' . $search . '%')
            ->orWhere('sales_bonus', 'like', '%' . $search . '%')
            ->orWhere('project_bonus', 'like', '%' . $search . '%')
            ->orWhere('other_bonus', 'like', '%' . $search . '%')
            ->orWhere('bpjs_tek_dues', 'like', '%' . $search . '%')
            ->orWhere('bpjs_kes_dues', 'like', '%' . $search . '%')
            ->get();

        return $payroll;
    }
}
