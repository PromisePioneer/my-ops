<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $description
 * @property int $sub_account_debit
 * @property int $sub_account_credit
 * @property float $initial_payment
 * @property int $status_confirmation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SubAccount $subAccountCredit
 * @property-read \App\Models\SubAccount $subAccountDebit
 *
 * @method static \Illuminate\Database\Eloquent\Builder|InitialJournal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InitialJournal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InitialJournal query()
 * @method static \Illuminate\Database\Eloquent\Builder|InitialJournal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InitialJournal whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InitialJournal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InitialJournal whereInitialPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InitialJournal whereStatusConfirmation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InitialJournal whereSubAccountCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InitialJournal whereSubAccountDebit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InitialJournal whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class InitialJournal extends Model
{
    use HasFactory;

    protected $table = 'initial_journal';

    protected $fillable = [
        'description',
        'sub_account_debit',
        'sub_account_credit',
        'initial_payment',
    ];

    public function subAccountDebit(): BelongsTo
    {
        return $this->belongsTo(SubAccount::class, 'sub_account_debit');
    }

    public function subAccountCredit(): BelongsTo
    {
        return $this->belongsTo(SubAccount::class, 'sub_account_credit');
    }
}
