<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;

use function App\Helper\formatDate;

class UserHasMealAllowance extends Model
{
    use HasFactory;

    protected $table = 'user_has_meal_allowances';
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
        return self::formattedData($data);
    }


    public function formattedData(LengthAwarePaginator $mealAllowance): LengthAwarePaginator
    {
        $data = $mealAllowance->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => formatDate($item->date),
                'nip' => $item->user->nip,
                'user_name' => $item->user->name,
                'type' => $item->type,
                'amount' => number_format($item->amount),
            ];
        });

        $mealAllowance->setCollection($data);
        return $mealAllowance;
    }
}
