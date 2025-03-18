<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class LeaveAndPermission extends Model
{
    use HasFactory, Searchable;

    protected $table = 'leaves_and_permissions';

    protected $fillable = [
        'start_date',
        'end_date',
        'user_id',
        'reason',
        'leaves_status',
        'confirmation_status',
        'sick_letter',
        'confirmation_reason',
        'acc_by',
    ];


    // relationship
    public function accBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acc_by');
    }

    public function getData(): Builder
    {
        return self::with('accBy', 'user');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'users.name' => $this->name
        ];
    }


    public function makeSearchableUsing(Collection $models): Collection
    {
        return $models->load('user');
    }
}
