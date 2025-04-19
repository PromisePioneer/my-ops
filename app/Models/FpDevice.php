<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Laravel\Scout\Searchable;


class FpDevice extends Model
{

    use Searchable;
    protected $table = 'fp_devices';

    protected $fillable = [
        'branch_id',
        'name',
        'ip_address',
        'serial_number',
        'online',
    ];


    public function toSearchableArray(): array
    {
        $this->loadMissing('branch');
        return [
            'serial_number' => $this->serial_number,
            'name' => $this->name,
            'ip_address' => $this->ip_address
        ];
    }

    public function makeSearchableUsing(Collection $models): Collection
    {
        return $models->load('branch');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function attendanceJobProgress(): HasOne
    {
        return $this->hasOne(AttendanceJobProgress::class, 'device_id')->latestOfMany();
    }

    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::when(!empty($search), function ($query) use ($search) {
            $query->where('serial_number', 'like', '%' . $search . '%');
        })->orderby('serial_number')->select('id', 'serial_number')->get();

        return $query->map(function ($c) {
            return [
                'id' => $c->serial_number,
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
