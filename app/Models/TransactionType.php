<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class TransactionType extends Model
{
    use Searchable;

    protected $table = 'transaction_types';
    protected $fillable = [
        'name',
        'debit_account_id',
        'credit_account_id'
    ];


    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name
        ];
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
