\<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickingStock extends Model
{
    protected $table = 'picking_stock';
    protected $fillable = [
        'stock_id',
        'approved_by_kca',
        'approved_by_stocker',
        'kca_id',
        'stocker_id',
    ];
}
