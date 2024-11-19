<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrder extends Model
{
    protected $table = 'purchase_orders';
    protected $fillable = [
        'subject',
        'contact_id',
        'offering_letter_id',
        'date',
        'po_number',
        'pic'
    ];


    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }


    public function picName(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic');
    }
}


