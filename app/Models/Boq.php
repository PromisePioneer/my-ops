<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Boq extends Model
{
    use HasFactory;

    protected $table = 'boq';
    protected $fillable = [
        'boq_number',
        'branch_id',
        'title',
        'date',
        'status',
        'submitter',
        'approved_by',
        'known_by',
        'attachment',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }


    public function submitterName(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitter');
    }


    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function knownBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'known_by');
    }
}
