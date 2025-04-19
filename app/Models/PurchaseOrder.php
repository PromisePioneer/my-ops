<?php

namespace App\Models;

use App\Models\Master\Common\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class PurchaseOrder extends Model
{
    use Searchable;
    protected $table = 'purchase_orders';
    protected $fillable = [
        'subject',
        'contact_id',
        'offering_letter_id',
        'date',
        'po_number',
        'pic',
        'status'
    ];


    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }


    public function picName(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic');
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'po_number' => $this->po_number,
            'subject' => $this->subject,
        ];
    }


}


