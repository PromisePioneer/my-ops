<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pole extends Model
{
    use HasFactory;

    protected $table = 'poles';
    protected $fillable = [
        'branch_id',
        'diameter',
        'length',
        'region',
        'code',
        'lat',
        'long',
        'cut_off_date',
    ];


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

}
