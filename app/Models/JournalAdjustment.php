<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalAdjustment extends Model
{
    use HasFactory;
    protected $table = 'journal_adjustment';
    protected $fillable = [
        'initial_journal_id',
        'description',
        'payment_date',
        'total_payment_per_month'
    ];

    public function initialJournal(): BelongsTo
    {
        return $this->belongsTo(InitialJournal::class, 'initial_journal_id');
    }
}
