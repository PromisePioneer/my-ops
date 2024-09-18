<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class BroadbandPacket extends Model
{
    use HasFactory;

    protected $table = 'broadband_packets';
    protected $fillable = [
        'branch_id',
        'name',
        'capacity',
        'price',
    ];


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function getData(Request $request): array
    {
        $search = $request->search;
        $query = self::orderby('name', 'asc')
            ->select('id', 'name', 'capacity');

        if ($search !== '') {
            $query->where('name', 'like', '%'.$search.'%');
        }

        $packet = $query->get();

        return $packet->map(function ($c) {
            return [
                'id' => $c->id,
                'text' => $c->name.' '.$c->capacity.' '.'Mbps',
            ];
        })->toArray();
    }

    public function getSelectedData(?int $packetId): array
    {
        $packet = self::where('id', $packetId)->first();


        return [
            'id' => $packet->id,
            'name' => $packet->name,
        ];
    }
}
