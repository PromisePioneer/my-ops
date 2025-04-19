<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class UserHasArea extends Model
{
    use Searchable;
    protected $table = 'user_has_area';
    protected $fillable = [
        'user_id',
        'area_id',
    ];

    public function toSearchableArray(): array
    {
        $this->loadMissing('user');
        return [
            'id' => $this->id
        ];
    }

    public function makeSearchableUsing(Collection $models): Collection
    {
        return $models->load('user');
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
