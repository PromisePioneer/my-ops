<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

use function App\Helper\formatDate;

class UserHasThrAllowance extends Model
{
    protected $table = 'user_has_thr_allowances';
    protected $fillable = [
        'user_id',
        'period',
        'amount',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function data(): LengthAwarePaginator
    {
        $data = self::with('user', 'user.identityInformation', 'user.jobInformation')->paginate(10);
        return self::formattedData($data);
    }

    private static function formattedData(LengthAwarePaginator $thr): LengthAwarePaginator
    {
        $data = $thr->getCollection()->map(function ($item) {
            $periodOfService = Carbon::parse($item->user->join_date)->diffInMonths(Carbon::now());
            return [
                'id' => $item->id,
                'user_id' => $item->user->id,
                'user_name' => $item->user->name,
                'date' => formatDate($item->period),
                'religion' => $item->user->identityInformation->religion,
                'employee_status' => $item->user->jobInformation->contract_status,
                'fixed_salary' => $item->user->jobInformation->fixed_salary,
                'period_of_service' => (int) $periodOfService.' Bulan',
                'total_thr' => number_format($item->amount),
            ];
        })->values();


        $thr->setCollection($data);
        return $thr;
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $query = self::with('user', 'user.identityInformation', 'user.jobInformation')
            ->where('period', 'like'.'%'.$search.'%')
            ->orWhere('amount', 'like'.'%'.$search.'%')
            ->orWhereHas('user', function ($query) use ($search) {
                $query->where('name', 'like'.'%'.$search.'%');
            })->paginate(10);

        self::formattedData($query);

        return $query;
    }
}
