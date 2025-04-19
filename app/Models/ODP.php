<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ODP extends Model
{
    use HasFactory;

    protected $table = 'odp';
    protected $fillable = [
        'branch_id',
        'name',
        'classification',
        'passive_splitter',
        'long',
        'lat',
        'max_capacity',
        'used_capacity',
        'cut_off_date',
    ];


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
