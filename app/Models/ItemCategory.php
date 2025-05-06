<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Laravel\Scout\Searchable;

class ItemCategory extends Model
{
    use HasFactory, Searchable;

    protected $table = 'item_categories';
    protected $fillable = [
        'name',
        'notes',
        'description',
    ];

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }


    public function itemCollections(): HasMany
    {
        return $this->hasMany(ItemCollection::class, 'category_id');
    }
}
