<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class TaxSetting extends Model
{
    use HasFactory, Searchable;

    protected $table = 'tax_settings';
    protected $fillable = [
        'name',
        'rate',
    ];

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }


}
