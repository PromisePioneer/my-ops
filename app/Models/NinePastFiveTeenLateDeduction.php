<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NinePastFiveTeenLateDeduction extends Model
{
    use HasFactory;

    protected $table = 'nine_past_fiveteen_late_deduction';
    protected $fillable = [
        'date',
        'kca_id',
        'technician_id',
        'total_amount_of_late',
        'total_deduction_amount',
    ];


    public function kca(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kca_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
