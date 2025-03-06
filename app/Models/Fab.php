<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Laravel\Scout\Searchable;

class Fab extends Model
{
    use Searchable;
    protected $table = 'fab';

    protected $fillable = [
        'po_id',
        'fab_number',
        'contract_number',
        'date',
        'pic',
        'created_by',
        'created_by',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function fabPic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic');
    }

    public function po(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
    }


    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::with('po')
            ->where('status', 1)
            ->orderby('fab_number', 'asc')
            ->when($search, function ($query) use ($search) {
                $query->where('fab_number', 'like', '%' . $search . '%')
                    ->where('fab_number', 'like', '%' . $search . '%');
            });
        $contact = $query->get();

        return $contact->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->fab_number . ' - ' . $item->po->contact->company_name,
            ];
        })->toArray();
    }

    public function getSelectedData(int $fabId): array
    {
        $fabId = self::where('id', $fabId)->first();

        return [
            'id' => $fabId->id,
            'name' => $fabId->fab_number,
        ];
    }


}
