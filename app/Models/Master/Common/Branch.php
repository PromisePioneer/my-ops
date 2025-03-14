<?php

namespace App\Models\Master\Common;

use App\Models\AccountTransaction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Branch extends Model
{
    use HasFactory, Searchable;

    protected $table = 'branches';

    protected $fillable = [
        'name',
        'code',
        'address',
        'parent_id',
    ];


    //relations
    public function accountTransaction(): HasMany
    {
        return $this->hasMany(AccountTransaction::class, 'branch_id');
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
        ];
    }


    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }


    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

}
