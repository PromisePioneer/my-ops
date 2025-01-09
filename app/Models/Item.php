<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class Item extends Model
{
    protected $table = 'items';
    protected $fillable = [
        'name',
        'category_id',
        'unit_type_id',
        'need_sn',
        'already_has_sn_on_item',
    ];


    public function centralWarehouseItem(): HasMany
    {
        return $this->hasMany(CentralWarehouseItem::class, 'item_id');
    }

    public function branchWarehouseItem(): HasMany
    {
        return $this->hasMany(BranchWarehouseItem::class, 'item_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }

    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class, 'unit_type_id');
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

    public function getSelectedData(int $itemId): array
    {
        $contact = self::where('id', $itemId)->first();

        return [
            'id' => $contact->id,
            'name' => $contact->name,
        ];
    }


}
