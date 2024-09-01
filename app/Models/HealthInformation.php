<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthInformation extends Model
{
    use HasFactory;

    protected $table = 'health_informations';

    protected $fillable = [
        'user_id',
        'disease',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRelatedUserHealthInformation(int $userId): Model|Builder|null
    {
        return self::with('user')->where('user_id', $userId)->first();
    }
}
