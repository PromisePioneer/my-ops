<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class AdditionalDeduction extends Model
{
    use HasFactory, Searchable;

    protected $table = 'additional_deduction';
    protected $fillable = [
        'date',
        'user_id',
        'type',
        'amount',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
