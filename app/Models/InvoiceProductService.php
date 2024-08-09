<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int $invoice_id
 * @property string $description
 * @property int $qty
 * @property float $unit_price
 * @property float $total_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Invoice $invoice
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceProductService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceProductService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceProductService query()
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceProductService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceProductService whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceProductService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceProductService whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceProductService whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceProductService whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceProductService whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InvoiceProductService whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
