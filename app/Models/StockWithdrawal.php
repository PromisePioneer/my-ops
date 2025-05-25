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
        'pic_id',
        'stocker_id',
        'status',
    ];


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function pic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic_id');
    }


    public function stocker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'stocker_id');
    }


    public function stockWithdrawalByEmployees(): HasMany
    {
        return $this->hasMany(StockWithdrawalByEmployee::class, 'stock_withdrawal_id');
    }


    public function stockWithdrawalItems(): HasMany
    {
        return $this->hasMany(StockWithdrawalItem::class, 'stock_withdrawal_id');
    }

}
