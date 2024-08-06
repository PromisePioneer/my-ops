<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserJobInformation extends Model
{
    protected $table = 'user_jobs_informations';

    protected $fillable = [
        'user_id',
        'department_id',
        'join_date',
        'fixed_salary',
        'placement_id',
        'contract_status',
        'bank_account_number',
        'bpjs_kes',
        'bpjs_ket',
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

    public function placement(): BelongsTo
    {
        return $this->belongsTo(UserPlacement::class, 'placement_id');
    }

    //eloquent
    public function getRelatedUserJobInformation(int $userId)
    {
        return self::with('department', 'placement')->where('user_id', $userId)->first();
    }
}
