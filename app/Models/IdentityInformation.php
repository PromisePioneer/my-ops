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
 * @property string $nik
 * @property string $date_of_birth
 * @property string $place_of_birth
 * @property string $gender
 * @property string $ktp_attachment
 * @property string $marital_status
 * @property string $married_status
 * @property string|null $home_address
 * @property string $phone_number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 *
 * @method static Builder|IdentityInformation newModelQuery()
 * @method static Builder|IdentityInformation newQuery()
 * @method static Builder|IdentityInformation query()
 * @method static Builder|IdentityInformation whereCreatedAt($value)
 * @method static Builder|IdentityInformation whereDateOfBirth($value)
 * @method static Builder|IdentityInformation whereGender($value)
 * @method static Builder|IdentityInformation whereHomeAddress($value)
 * @method static Builder|IdentityInformation whereId($value)
 * @method static Builder|IdentityInformation whereKtpAttachment($value)
 * @method static Builder|IdentityInformation whereMaritalStatus($value)
 * @method static Builder|IdentityInformation whereMarriedStatus($value)
 * @method static Builder|IdentityInformation whereNik($value)
 * @method static Builder|IdentityInformation wherePhoneNumber($value)
 * @method static Builder|IdentityInformation wherePlaceOfBirth($value)
 * @method static Builder|IdentityInformation whereUpdatedAt($value)
 * @method static Builder|IdentityInformation whereUserId($value)
 *
 * @mixin Eloquent
 */
class IdentityInformation extends Model
{
    use HasFactory;

    protected $table = 'user_identity_informations';

    protected $fillable = [
        'user_id',
        'nik',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'religion',
        'home_address',
        'phone_number',
        'ktp_attachment',
        'marital_status',
        'married_status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //eloquent
    public function getRelatedUserIdentityInformation(int $userId): Model|Builder|IdentityInformation|null
    {
        return self::where('user_id', $userId)->first();
    }
}
