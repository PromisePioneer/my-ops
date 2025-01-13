<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class CentralWarehouseItem extends Model
{
    protected $table = 'central_warehouse_items';
    protected $fillable = [
        'po_id',
        'date',
        'item_id',
        'warehouse_id',
        'name',
        'qty',
        'from_po'
    ];


    public function po(): BelongsTo
    {
        return $this->belongsTo(GoodsPurchaseOrder::class, 'po_items_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'item_id');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
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


    public function centralWarehouseStock(): HasMany
    {
        return $this->hasMany(CentralWarehouseStock::class, 'central_warehouse_item_id');
    }

    public function getSelectedData(int $centralWarehouseStockId): array
    {
        $centralWarehouseStock = self::where('id', $centralWarehouseStockId)->first();

        return [
            'id' => $centralWarehouseStock->id,
            'name' => $centralWarehouseStock->name,
        ];
    }


}
