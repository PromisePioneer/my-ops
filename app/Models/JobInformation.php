<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobInformation extends Model
{
    protected $table = 'user_jobs_informations';

    protected $fillable = [
        'user_id',
        'fixed_salary',
        'position_allowance',
        'week_holiday',
        'contract_status',
        'bank_account_number',
        'bpjs_kes',
        'no_kpj',
        'bpjs_ket',
        'no_kis'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRelatedUserJobInformation(?int $userId): Model|Builder|null
    {
        return self::where('user_id', $userId)->first();
    }
}
