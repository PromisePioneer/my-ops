<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravel\Scout\Searchable;

class SP extends Model
{
    use HasFactory, Searchable;

    protected $table = 'sp';

    protected $fillable = [
        'branch_id',
        'user_id',
        'sp_number',
        'sp_type',
        'created_by',
        'reason',
        'description',
        'punished_by',
        'start_date',
        'end_date',
        'expired_if_has_new_sp',
        'list_of_reason',
        'punished_by_role_id',
        'known_by_user_id',
        'known_by_role_id'
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function punishedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'punished_by');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function knownBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'known_by_user_id');
    }


    public function knownByRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'known_by_role_id');
    }


    public function punishedByRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'punished_by_role_id');
    }


    public function toSearchableArray(): array
    {
        return [
            'users.name' => '',
        ];
    }

}
