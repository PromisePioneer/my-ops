<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $institution
 * @property string $major
 * @property string $graduation_year
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 *
 * @method static Builder|Education newModelQuery()
 * @method static Builder|Education newQuery()
 * @method static Builder|Education query()
 * @method static Builder|Education whereCreatedAt($value)
 * @method static Builder|Education whereGraduationYear($value)
 * @method static Builder|Education whereId($value)
 * @method static Builder|Education whereInstitution($value)
 * @method static Builder|Education whereMajor($value)
 * @method static Builder|Education whereName($value)
 * @method static Builder|Education whereUpdatedAt($value)
 * @method static Builder|Education whereUserId($value)
 *
 * @mixin Eloquent
 */
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
