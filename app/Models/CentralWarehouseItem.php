<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class CentralWarehouseItem extends Model
{
    protected $table = 'central_warehouse_items';
    protected $fillable = [
        'po_items_id',
        'item_id',
        'category_id',
        'name',
        'qty',
        'unit_type_id'
    ];


    public function po(): BelongsTo
    {
        return $this->belongsTo(PoListOfItem::class, 'po_items_id');
    }

    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
    }


    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::where('qty', '>', 0)->orderby('name', 'asc');

        if ($search !== '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->where('name', 'like', '%' . $request->search . '%');
        }
        $contact = $query->get(['id', 'name', 'qty']);

        return $contact->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => '[' . $item->name . ']' . ' ' . 'Qty :' . $item->qty,
            ];
        })->toArray();
    }

    public function getSelectedData(int $centralWarehouseStockId): array
    {
        $centralWarehouseStock = self::where('id', $centralWarehouseStockId)->first();

        return [
            'id' => $centralWarehouseStock->id,
            'name' => $centralWarehouseStock->name,
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
