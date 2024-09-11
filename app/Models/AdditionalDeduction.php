<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;

use function App\Helper\formatDate;

class AdditionalDeduction extends Model
{
    use HasFactory;

    protected $table = 'additional_deduction';
    protected $fillable = [
        'date',
        'user_id',
        'type',
        'amount',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function data(): LengthAwarePaginator
    {
        $data = self::with('user')->paginate(10);
        self::formattedData($data);
        return $data;
    }


    public function formattedData(LengthAwarePaginator $additionalDeduction): LengthAwarePaginator
    {
        $data = $additionalDeduction->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => formatDate($item->date),
                'user_name' => $item->user->name,
                'type' => $item->type,
                'amount' => number_format($item->amount),
            ];
        });

        $additionalDeduction->setCollection($data);
        return $additionalDeduction;
    }
}
