<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;


class FpDevice extends Model
{
    protected $table = 'fp_devices';

    protected $fillable = [
        'branch_id',
        'name',
        'serial_number',
        'online',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function getDataWithPagination(int $perPage): LengthAwarePaginator
    {
        return self::with('branch')->paginate($perPage);
    }

    public function searchData(Request $request): Collection|array
    {
        $search = $request->input('search');

        return self::with('branch')
            ->where('ip_address', 'like', '%'.$search.'%')
            ->where('name', 'like', '%'.$search.'%')
            ->where('serial_number', 'like', '%'.$search.'%')
            ->get();
    }


    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::when(!empty($search), function ($query) use ($search) {
            $query->where('serial_number', 'like', '%' . $search . '%');
        })->orderby('serial_number')->select('id', 'serial_number')->get();

        return $query->map(function ($c) {
            return [
                'id' => $c->id,
                'text' => $c->serial_number,
            ];
        })->toArray();
    }


    public function getSelectedData(?int $branchId = null): ?array
    {
        $branch = self::where('id', $branchId)->first();

        if ($branchId === null) {
            return null;
        }

        return [
            'id' => $branch->id,
            'name' => $branch->sn,
        ];
    }
}
