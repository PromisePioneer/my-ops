<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FabHasSKL extends Model
{
    protected $table = 'fab_has_skl';
    protected $fillable = [
        'fab_id',
        'skl_id',
    ];


    public function fab(): BelongsTo
    {
        return $this->belongsTo(Fab::class, 'fab_id');
    }

    public function skl(): BelongsTo
    {
        return $this->belongsTo(SKL::class, 'skl_id');
    }
}
