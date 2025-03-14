<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use App\Models\Master\Common\UnitType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class Transaction extends Model
{
    use Searchable;
    protected $table = 'transactions';
    protected $fillable = [
        'type',
        'transaction_number',
        'branch_id',
        'date',
        'detail',
        'qty',
        'unit_type_id',
        'unit_price',
        'total_price',
        'status',
        'goods_id',
        'debit_account_id',
        'credit_account_id'
    ];


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }

    public function debitAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'debit_account_id');
    }

    public function creditAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'credit_account_id');
    }


    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }


    public function toSearchableArray(): array
    {
        return [
            'transaction_number' => $this->transaction_number,
        ];
    }
}
