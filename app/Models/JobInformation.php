<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Department $department
 * @property-read User $user
 *
 * @method static Builder|JobInformation newModelQuery()
 * @method static Builder|JobInformation newQuery()
 * @method static Builder|JobInformation query()
 * @method static Builder|JobInformation whereAbsentId($value)
 * @method static Builder|JobInformation whereBankAccountNumber($value)
 * @method static Builder|JobInformation whereBpjsKes($value)
 * @method static Builder|JobInformation whereBpjsKet($value)
 * @method static Builder|JobInformation whereContractFile($value)
 * @method static Builder|JobInformation whereContractStatus($value)
 * @method static Builder|JobInformation whereCreatedAt($value)
 * @method static Builder|JobInformation whereDepartmentId($value)
 * @method static Builder|JobInformation whereEmpCode($value)
 * @method static Builder|JobInformation whereFixedSalary($value)
 * @method static Builder|JobInformation whereId($value)
 * @method static Builder|JobInformation whereNoKis($value)
 * @method static Builder|JobInformation whereNoKpj($value)
 * @method static Builder|JobInformation whereSkFile($value)
 * @method static Builder|JobInformation whereUpdatedAt($value)
 * @method static Builder|JobInformation whereUserId($value)
 *
 * @mixin Eloquent
 */
class JobInformation extends Model
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
