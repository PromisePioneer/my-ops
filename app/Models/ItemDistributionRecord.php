<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemDistributionRecord extends Model
{
    protected $table = 'item_distribution_records';
    protected $fillable = [
        'po_number',
        'date',
        'item_code',
        'item_name',
        'qty',
        'from',
        'to',
    ];
}
