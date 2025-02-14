<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountTransaction extends Model
{
    use HasFactory;

    protected $table = 'account_transactions';
    protected $fillable = [
        'branch_id',
        'date',
        'account_id',
        'description',
        'transaction_type',
        'entries_type',
        'amount',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function getCurrentPPNOnInvoice($description): self
    {
        return self::whereHas('account')->whereHas('account', function ($query) {
            $query->where('code', '213-01');
        })->where('description', $description)->first();
    }
}
