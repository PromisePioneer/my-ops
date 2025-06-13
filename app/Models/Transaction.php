<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use App\Models\Master\Common\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Transaction extends Model
{
    use Searchable;

    protected $table = 'transactions';
    protected $fillable = [
        'transaction_number',
        'branch_id',
        'supplier_id',
        'date',
        'item_id',
        'qty',
        'type',
        'unit_price',
        'total_price',
        'detail',
        'debit_account_id',
        'credit_account_id',
        'locked_status',
        'created_by',
        'status',
        'final_notes',
        'approved_by',
        'attachment',
        'tax_invoice',
        'qty_in_meter',
    ];


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


    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemCollection::class, 'item_id');
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


    public function draftStock(): HasMany
    {
        return $this->hasMany(DraftStock::class);
    }


    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }
}
