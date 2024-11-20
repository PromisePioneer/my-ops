<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class PurchaseOrder extends Model
{
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


    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::with('contact')->where('status', 1)->orderby('po_number', 'asc');
        if ($search !== '') {
            $query->where('po_number', 'like', '%' . $request->search . '%')
                ->where('po_number', 'like', '%' . $request->search . '%');
        }
        $contact = $query->get();

        return $contact->map(function ($po) {

            return [
                'id' => $po->id,
                'text' => $po->po_number . ' - ' . $po->contact->company_name,
            ];
        })->toArray();
    }

    public function getSelectedData(int $poId): array
    {
        $po = self::where('id', $poId)->first();

        return [
            'id' => $po->id,
            'name' => $po->po_number . ' - ' . $po->contact->company_name,
        ];
    }
}


