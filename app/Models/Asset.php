<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    use HasFactory;

    protected $table = 'assets';
    protected $fillable = [
        'branch_id',
        'account_id',
        'name',
        'unit',
        'useful_life',
        'price_per_unit',
        'price_at_first_recieved',
        'depreciation_rate',
    ];


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
