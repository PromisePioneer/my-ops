<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $emp_code
 * @property string $absent_id
 * @property int $user_id
 * @property int $department_id
 * @property float $fixed_salary
 * @property string $contract_status
 * @property string $bank_account_number
 * @property string $bpjs_kes
 * @property string|null $no_kpj
 * @property string $bpjs_ket
 * @property string|null $no_kis
 * @property string $sk_file
 * @property string $contract_file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Department $department
 * @property-read \App\Models\User $user
 *
 * @method static Builder|UserJobInformation newModelQuery()
 * @method static Builder|UserJobInformation newQuery()
 * @method static Builder|UserJobInformation query()
 * @method static Builder|UserJobInformation whereAbsentId($value)
 * @method static Builder|UserJobInformation whereBankAccountNumber($value)
 * @method static Builder|UserJobInformation whereBpjsKes($value)
 * @method static Builder|UserJobInformation whereBpjsKet($value)
 * @method static Builder|UserJobInformation whereContractFile($value)
 * @method static Builder|UserJobInformation whereContractStatus($value)
 * @method static Builder|UserJobInformation whereCreatedAt($value)
 * @method static Builder|UserJobInformation whereDepartmentId($value)
 * @method static Builder|UserJobInformation whereEmpCode($value)
 * @method static Builder|UserJobInformation whereFixedSalary($value)
 * @method static Builder|UserJobInformation whereId($value)
 * @method static Builder|UserJobInformation whereNoKis($value)
 * @method static Builder|UserJobInformation whereNoKpj($value)
 * @method static Builder|UserJobInformation whereSkFile($value)
 * @method static Builder|UserJobInformation whereUpdatedAt($value)
 * @method static Builder|UserJobInformation whereUserId($value)
 *
 * @mixin \Eloquent
 */
class UserJobInformation extends Model
{
    protected $table = 'user_jobs_informations';

    protected $fillable = [
        'emp_code',
        'absent_id',
        'user_id',
        'department_id',
        'fixed_salary',
        'placement_id',
        'contract_status',
        'bank_account_number',
        'bpjs_kes',
        'no_kis',
        'bpjs_ket',
        'no_kpj',
        'sk_file',
        'contract_file',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    //eloquent
    public function getRelatedUserJobInformation(int $userId): Model|Builder|null
    {
        return self::with('department')->where('user_id', $userId)->first();
    }
}
