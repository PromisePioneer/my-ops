<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumedStock extends Model
{
protected $table = 'consumed_stocks';
    protected $fillable = [
        'branch_id',
        'stock_id',
        'debit_account_id',
        'credit_account_id',
        'qty',
        'submitted_by',
    ];


    public function goods(): BelongsTo
    {
        return $this->belongsTo(ItemCollection::class, 'goods_stock_id');
    }


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function debitAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'debit_account_id');
    }

    public function creditAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'credit_account_id');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}
