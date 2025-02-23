<?php

namespace App\Models;

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
        return [
            'users.name' => '',
        ];

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
