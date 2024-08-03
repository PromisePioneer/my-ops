<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BastProduct extends Model
{
    protected $table = 'bast_products';
    protected $fillable = [
        'bast_id',
        'product_name',
        'qty',
        'serial_number',
        'description'
    ];

    public function bast(): BelongsTo
    {
        return $this->belongsTo(Bast::class, 'bast_id');
    }

    //eloquent
    public function getData(int $bastId)
    {
        return self::with('bast')
            ->where('bast_id', $bastId)
            ->get();
    }
}
