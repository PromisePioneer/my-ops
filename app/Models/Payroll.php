<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $period_start
 * @property string $period_end
 * @property string $salary_date
 * @property float|null $positional_allowance
 * @property float|null $meal_allowance
 * @property float|null $transportation_allowance
 * @property float|null $overtime_allowance
 * @property float|null $sales_bonus
 * @property float|null $project_bonus
 * @property float|null $other_bonus
 * @property float|null $bpjs_tek_dues
 * @property float|null $bpjs_kes_dues
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereBpjsKesDues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereBpjsTekDues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereMealAllowance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereOtherBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereOvertimeAllowance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll wherePeriodEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll wherePeriodStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll wherePositionalAllowance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereProjectBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereSalaryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereSalesBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereTransportationAllowance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payroll whereUserId($value)
 * @mixin \Eloquent
 */
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
        'bpjs_kes_dues',
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
            $query->where('name', 'like', '%'.$search.'%');
        })->orWhere('period_start', 'like', '%'.$search.'%')
            ->orWhere('period_end', 'like', '%'.$search.'%')
            ->orWhere('salary_date', 'like', '%'.$search.'%')
            ->orWhere('positional_allowance', 'like', '%'.$search.'%')
            ->orWhere('meal_allowance', 'like', '%'.$search.'%')
            ->orWhere('transportation_allowance', 'like', '%'.$search.'%')
            ->orWhere('overtime_allowance', 'like', '%'.$search.'%')
            ->orWhere('sales_bonus', 'like', '%'.$search.'%')
            ->orWhere('project_bonus', 'like', '%'.$search.'%')
            ->orWhere('other_bonus', 'like', '%'.$search.'%')
            ->orWhere('bpjs_tek_dues', 'like', '%'.$search.'%')
            ->orWhere('bpjs_kes_dues', 'like', '%'.$search.'%')
            ->get();

        return $payroll;
    }
}
