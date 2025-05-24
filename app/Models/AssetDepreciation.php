<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetDepreciation extends Model
{
    use HasFactory;

    protected $table = 'assets_depreciation';
    protected $fillable = [
        'asset_id',
        'depreciation_date',
        'total_depreciation_in_month',
        'depreciation_amount',
    ];


    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
