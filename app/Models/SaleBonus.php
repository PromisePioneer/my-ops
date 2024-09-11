<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;

use function App\Helper\formatDate;

class SaleBonus extends Model
{
    use HasFactory;

    protected $table = 'sales_bonus';
    protected $fillable = [
        'date_active',
        'customer_name',
        'user_id',
        'packet_id',
        'discount',
        'amount',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function packet(): BelongsTo
    {
        return $this->belongsTo(BroadbandPacket::class, 'packet_id');
    }


    public function data(): LengthAwarePaginator
    {
        $data = self::with('user', 'packet')->paginate(10);
        return self::formattedData($data);
    }

    public function formattedData(LengthAwarePaginator $saleBonusData): LengthAwarePaginator
    {
        $query = $saleBonusData->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'date_active' => formatDate($item->date_active),
                'customer_name' => $item->customer_name,
                'packet_name' => $item->packet->name,
                'packet_price' => number_format($item->packet->price),
                'discount' => number_format(($item->packet->price / 100) * $item->discount) ?? null,
                'sales' => $item->user->name,
                'amount' => number_format($item->amount),
            ];
        });


        $saleBonusData->setCollection($query);

        return $saleBonusData;
    }
}
