<?php

namespace App\Models\Master\Common;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class ServiceCategory extends Model
{
    use Searchable;

    protected $table = 'service_categories';
    protected $fillable = [
        'name',
        'unit_price',
        'capacity',
    ];


    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
