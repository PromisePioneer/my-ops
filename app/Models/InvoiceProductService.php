<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceProductService extends Model
{
    protected $table = 'invoice_services';
    protected $fillable = [
        'invoice_id',
        'description',
        'qty',
        'unit_price',
        'total_price',
    ];


    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }


    public function getSelectedInvoiceProductServices($invoice)
    {
        return self::where('invoice_id', $invoice->id)->get();
    }
}
