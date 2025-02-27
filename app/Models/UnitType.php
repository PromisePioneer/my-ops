<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Laravel\Scout\Searchable;

class UnitType extends Model
{
    use Searchable;
    protected $table = 'unit_types';
    protected $fillable = [
        'name',
    ];


    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

}
