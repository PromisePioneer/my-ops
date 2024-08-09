<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserIdentityInformation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserIdentityInformation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserIdentityInformation query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserIdentityInformation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserIdentityInformation whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserIdentityInformation whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserIdentityInformation whereHomeAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserIdentityInformation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserIdentityInformation whereKtpAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserIdentityInformation whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserIdentityInformation whereMarriedStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserIdentityInformation whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserIdentityInformation wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserIdentityInformation wherePlaceOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserIdentityInformation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserIdentityInformation whereUserId($value)
 * @mixin \Eloquent
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
    public function getRelatedUserIdentityInformation(int $userId)
    {
        return self::where('user_id', $userId)->first();
    }
}
