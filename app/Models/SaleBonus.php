<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleBonus extends Model
{
    use HasFactory;

    protected $table = 'sales_bonus';
    protected $fillable = [
        'date_active',
        'customer_name',
        'user_id',
        'packet_id',
        'amount',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function packet(): BelongsTo
    {
        return $this->belongsTo(InternetPackage::class, 'packet_id');
    }
}
