<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Laravel\Scout\Searchable;

class BroadbandPacket extends Model
{
    use Searchable;

    protected $table = 'broadband_packets';
    protected $fillable = [
        'name',
        'capacity',
        'price',
    ];


    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'capacity' => $this->capacity,
            'price' => $this->price,
        ];
    }
}
