<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravel\Scout\Searchable;

class SP extends Model
{
    use HasFactory, Searchable;

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
        'start_date',
        'end_date',
        'expired_if_has_new_sp',
        'list_of_reason',
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

    //eloquent

    private static function formattedData(LengthAwarePaginator $sp): void
    {
        $formattedData = $sp->getCollection()->map(function ($item) {
            $isExpired = false;

            $spActive = $item->where('expired_if_has_new_sp', 0)->get();

            foreach ($spActive as $active) {
                if ($active?->id === $item->id) {
                    $isExpired = true;
                }

                if ($active?->id === $item->id && $item->end_date < Carbon::now()) {
                    $isExpired = false;
                }
            }

            return [
                'id' => $item->id,
                'branch_name' => $item->branch->name ?? null,
                'user_id' => "({$item->user->nip}) {$item->user->name}",
                'sp_number' => $item->sp_number,
                'date' => Carbon::parse($item->start_date)->format('d/m/Y').' - '.Carbon::parse($item->end_date)->format('d/m/Y'),
                'expired' => $isExpired,
                'sp_type' => $item->sp_type,
                'punished_by' => $item->punishedBy?->name,
                'created_by' => $item->createdBy->name,
            ];
        })->values();

        $sp->setCollection($formattedData);
    }


    public function toSearchableArray(): array
    {
        return [
            'users.name' => '',
        ];
    }

}
