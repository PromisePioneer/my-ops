<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Education extends Model
{
    use HasFactory;

    protected $table = 'educations';

    protected $fillable = [
        'user_id',
        'level',
        'institution',
        'major',
        'graduation_year',
        'certificate_of_graduation',
        'gpa',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRelatedUserEducation(?int $userId): Model|Education|Builder|null
    {
        return self::where('user_id', $userId)->first();
    }
}
