<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationCertificate extends Model
{
    use HasFactory;

    protected $table = 'education_certificates';

    protected $fillable = [
        'user_id',
        'organization',
        'name',
        'year',
        'file',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRelatedUserEducationCertificate(int $userId): Collection|array
    {
        return self::with('user')
            ->where('user_id', $userId)
            ->get();
    }
}
