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
 * @method static Builder|UserIdentityInformation newModelQuery()
 * @method static Builder|UserIdentityInformation newQuery()
 * @method static Builder|UserIdentityInformation query()
 * @method static Builder|UserIdentityInformation whereCreatedAt($value)
 * @method static Builder|UserIdentityInformation whereDateOfBirth($value)
 * @method static Builder|UserIdentityInformation whereGender($value)
 * @method static Builder|UserIdentityInformation whereHomeAddress($value)
 * @method static Builder|UserIdentityInformation whereId($value)
 * @method static Builder|UserIdentityInformation whereKtpAttachment($value)
 * @method static Builder|UserIdentityInformation whereMaritalStatus($value)
 * @method static Builder|UserIdentityInformation whereMarriedStatus($value)
 * @method static Builder|UserIdentityInformation whereNik($value)
 * @method static Builder|UserIdentityInformation wherePhoneNumber($value)
 * @method static Builder|UserIdentityInformation wherePlaceOfBirth($value)
 * @method static Builder|UserIdentityInformation whereUpdatedAt($value)
 * @method static Builder|UserIdentityInformation whereUserId($value)
 *
 * @mixin Eloquent
 */
class UserIdentityInformation extends Model
{
    use HasFactory;

    protected $table = 'user_identity_informations';

    protected $fillable = [
        'user_id',
        'nik',
        'date_of_birth',
        'place_of_birth',
        'gender',
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
    public function getRelatedUserIdentityInformation(int $userId): Model|Builder|UserIdentityInformation|null
    {
        return self::where('user_id', $userId)->first();
    }
}
