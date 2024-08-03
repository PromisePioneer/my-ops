<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InitialJournal extends Model
{
    use HasFactory;

    protected $table = 'initial_journal';
    protected $fillable = [
        'description',
        'sub_account_debit',
        'sub_account_credit',
        'initial_payment'
    ];


    public function subAccountDebit(): BelongsTo
    {
        return  $this->belongsTo(SubAccount::class, 'sub_account_debit');
    }

    public function subAccountCredit(): BelongsTo
    {
        return  $this->belongsTo(SubAccount::class, 'sub_account_credit');
    }
}
