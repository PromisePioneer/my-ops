<?php

namespace App\Models\Master\Common;

use App\Models\ItemCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class UnitType extends Model
{
    use Searchable, SoftDeletes;

    protected $table = 'unit_types';

    protected $fillable = [
        'name',
    ];


    public function itemCollections(): HasMany
    {
        return $this->hasMany(ItemCollection::class);
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public function ifRelatedDataExists($query): bool
    {
        return $query->itemCollection()->exists();
    }

}
