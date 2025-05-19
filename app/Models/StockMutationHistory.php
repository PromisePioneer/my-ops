<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMutationHistory extends Model
{
    protected $table = 'mutation_histories';
    protected $fillable = [
        'date',
        'old_branch_id',
        'new_branch_id',
        'item_id',
        'stocker_id'
    ];


    public function oldBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'old_branch_id');
    }


    public function newBranch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'new_branch_id');
    }


    public function item(): BelongsTo
    {
        return $this->belongsTo(ItemCollection::class, 'item_id');
    }


    public function stocker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'stocker_id');
    }
}
