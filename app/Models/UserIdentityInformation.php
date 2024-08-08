<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
