<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

use function App\Helper\formatDate;

class UserHasOvertime extends Model
{
    use HasFactory;

    protected $table = 'user_has_overtime';
    protected $fillable = [
        'user_id',
        'date',
        'hours',
        'reason',
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

    private static function formattedData(LengthAwarePaginator $overtimeData): LengthAwarePaginator
    {
        $data = $overtimeData->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => formatDate($item->date),
                'user_name' => $item->user->name,
                'total_hours' => $item->hours,
                'reason' => $item->reason,
                'amount' => 'Rp '.number_format($item->amount),
            ];
        });

        $overtimeData->setCollection($data);
        return $overtimeData;
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = self::with('user');

        if (!empty($search)) {
            $query->where('date', 'like', '%'.$search.'%')->orWhereHas('user', function ($query) use ($search) {
                $query->where('nip', 'like', '%'.$search.'%');
                $query->where('name', 'like', '%'.$search.'%');
            });
        }


        return $query->paginate(10);
    }

}
