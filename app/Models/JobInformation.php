<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class JobInformation extends Model
{
    protected $table = 'user_jobs_informations';

    protected $fillable = [
        'user_id',
        'position_allowance',
        'fixed_salary',
        'contract_status',
        'bank_account_number',
        'bpjs_kes',
        'no_kpj',
        'bpjs_ket',
        'no_kis',
        'week_holiday'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    //eloquent
    public function data(): LengthAwarePaginator
    {
        $data = self::with('user')->paginate(10);
        return self::formattedData($data);
    }

    private static function formattedData($positionAllowanceData)
    {
        $data = $positionAllowanceData->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'user_id' => $item->user->id,
                'nip' => $item->user->nip,
                'name' => $item->user->name,
                'position_allowance' => 'Rp . '.number_format($item->position_allowance),
            ];
        });

        $positionAllowanceData->setCollection($data);
        return $positionAllowanceData;
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $query = self::with('user');
        if (!empty($search)) {
            $query->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->paginate(10);
        return self::formattedData($data);
    }

    public function getRelatedUserJobInformation(?int $userId): Model|Builder|null
    {
        return self::where('user_id', $userId)->first();
    }
}
