<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property string $payment_date
 * @property int $initial_journal_id
 * @property string $description
 * @property float $total_payment_per_month
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\InitialJournal $initialJournal
 * @method static \Illuminate\Database\Eloquent\Builder|JournalAdjustment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JournalAdjustment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JournalAdjustment query()
 * @method static \Illuminate\Database\Eloquent\Builder|JournalAdjustment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalAdjustment whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalAdjustment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalAdjustment whereInitialJournalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalAdjustment wherePaymentDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalAdjustment whereTotalPaymentPerMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JournalAdjustment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class JournalAdjustment extends Model
{
    use HasFactory;

    protected $table = 'journal_adjustment';

    protected $fillable = [
        'initial_journal_id',
        'description',
        'payment_date',
        'total_payment_per_month',
    ];

    public function initialJournal(): BelongsTo
    {
        return $this->belongsTo(InitialJournal::class, 'initial_journal_id');
    }
}
