<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockMutation extends Model
{
    protected $table = 'stock_mutations';
    protected $fillable = [
        'stock_mutation_number',
        'date',
        'old_branch_id',
        'new_branch_id',
        'sender_id',
        'receiver_id',
        'description',
        'sender_signature',
        'receiver_signature',
        'status',
    ];


    public function stockMutationItems(): HasMany
    {
        return $this->hasMany(StockMutationItem::class, 'stock_mutation_id');
    }

    public function oldBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'old_branch_id');
    }


    public function newBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'new_branch_id');
    }


    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
