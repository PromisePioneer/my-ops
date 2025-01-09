<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class Warehouse extends Model
{
    protected $table = 'warehouses';
    protected $fillable = [
        'name',
        'code'
    ];


    public function centralWarehouseItem(): HasMany
    {
        return $this->hasMany(CentralWarehouseItem::class, 'warehouse_id');
    }

    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::orderby('name', 'asc');
        if ($search !== '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->where('name', 'like', '%' . $request->search . '%');
        }
        $contact = $query->get(['id', 'name']);

        return $contact->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        })->toArray();
    }

    public function getSelectedData(int $warehouseId): array
    {
        $warehouse = self::where('id', $warehouseId)->first();

        return [
            'id' => $warehouse->id,
            'name' => $warehouse->name,
        ];
    }
}
