<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyInformation extends Model
{
    use HasFactory;

    protected $table = 'family_informations';

    protected $fillable = [
        'user_id',
        'partner_name',
        'child',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
