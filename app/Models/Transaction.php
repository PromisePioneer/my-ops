<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class Transaction extends Model
{

    use Searchable;

    protected $table = 'transactions';
    protected $fillable = [
        'branch_id',
        'transaction_type_id',
        'date',
        'transaction_number',
        'detail',
        'amount',
    ];


    public function toSearchableArray(): array
    {
        return [
            'transaction_number' => $this->transaction_number,
        ];
    }

    public function transactionType(): BelongsTo
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id');
    }
}
