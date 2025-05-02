<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockWithdrawalByEmployee extends Model
{
    protected $table = 'stock_withdrawals_by_employee';
    protected $fillable = [
        'stock_withdrawal_id',
        'user_id',
    ];


    public function stockWithdrawal(): BelongsTo
    {
        return $this->belongsTo(StockWithdrawal::class, 'stock_withdrawal_id');
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
