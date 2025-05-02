<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockWithdrawal extends Model
{
    protected $table = 'stock_withdrawals';
    protected $fillable = [
        'branch_id',
        'date',
        'description',
        'kca_id',
        'stocker_id',
    ];


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function kca(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kca_id');
    }


    public function stocker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'stocker_id');
    }


    public function stockWithdrawalByEmployee(): HasMany
    {
        return $this->hasMany(StockWithdrawalByEmployee::class, 'stock_withdrawal_id');
    }


    public function stockWithdrawalItem()
    {
        return $this->hasMany(StockWithdrawalItem::class, 'stock_withdrawal_id');
    }

}
