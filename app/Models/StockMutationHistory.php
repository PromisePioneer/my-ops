<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMutationHistory extends Model
{
    protected $table = 'stock_mutation_histories';
    protected $fillable = [
        'old_branch_id',
        'old_stock_id',
        'new_branch_id',
        'new_stock_id',
        'description',
        'debit_account_id',
        'credit_account_id',
        'qty',
    ];


    public function oldBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'old_branch_id');
    }

    public function oldStock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, 'old_stock_id');
    }


    public function newBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'new_branch_id');
    }


    public function newStock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, 'new_stock_id');
    }

    public function debitAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'debit_account_id');
    }

    public function creditAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'credit_account_id');
    }


}
