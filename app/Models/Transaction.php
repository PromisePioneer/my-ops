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
        'item_id',
        'debit_account_id',
        'credit_account_id',
        'locked_status',
        'created_by',
        'confirmation_status',
        'confirmed_by',
        'excuses',
        'final_status',
        'approved_by',
        'final_excuses',
        'confirmation_excuses'
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


    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemCollection::class, 'item_id');
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }


    public function toSearchableArray(): array
    {
        return [
            'transaction_number' => $this->transaction_number,
        ];
    }
}
