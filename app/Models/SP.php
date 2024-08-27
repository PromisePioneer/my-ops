<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SP extends Model
{
    use HasFactory;

    protected $table = 'sp';
    protected $fillable = [
        'branch_id',
        'user_id',
        'sp_number',
        'sp_type',
        'created_by',
        'reason',
        'description',
        'punished_by',
        'date',
        'list_of_reason'
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function punishedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'punished_by');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function getDataWithPagination(int $perPage): LengthAwarePaginator
    {
        $sp = self::with('createdBy', 'user', 'branch')->paginate($perPage);

        self::formattedData($sp);
        return $sp;
    }

    //eloquent

    private static function formattedData(LengthAwarePaginator $sp): void
    {
        $formattedData = $sp->getCollection()->map(function ($sp) {
            return [
                'id' => $sp->id,
                'branch_name' => $sp->branch->name ?? null,
                'user_id' => $sp->user->name,
                'sp_number' => $sp->sp_number,
                'date' => Carbon::parse($sp->start_date)->format('d/m/Y').' - '.Carbon::parse($sp->end_date)->format('d/m/Y'),
                'sp_type' => $sp->sp_type,
                'punished_by' => $sp->punishedBy?->name,
                'created_by' => $sp->createdBy->name,
            ];
        })->values();

        $sp->setCollection($formattedData);
    }

    public function searchDataWithPagination(Request $request, int $perPage): LengthAwarePaginator
    {
        $search = $request->input('search');

        $sp = self::whereHas('user', function ($query) use ($search) {
            $query->where('name', 'like', '%'.$search.'%');
            $query->orWhere('nip', 'like', '%'.$search.'%');
        })->orWhere('sp_number', 'like', '%'.$search.'%')->paginate($this->perPage);

        self::formattedData($sp);
        return $sp;
    }

}
