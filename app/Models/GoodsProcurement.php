<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodsProcurement extends Model
{
    protected $table = 'goods_procurement';
    protected $fillable = [
        'branch_id',
        'procurement_number',
        'date',
        'for_period',
        'submitter_id',
        'known_by',
        'approved_by'
    ];


    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitter_id');
    }

    public function knownBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'known_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
