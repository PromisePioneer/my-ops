<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class FOCable extends Model
{
    use HasFactory;

    protected $table = 'fo_cables';
    protected $fillable = [
        'branch_id',
        'segment_id',
        'classification',
        'cable_placement',
        'cable_address',
        'total_core',
        'starting_point_lat',
        'starting_point_long',
        'ending_point_lat',
        'ending_point_long',
        'length',
        'cut_off_date',
    ];


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::orderby('segment_id')
            ->where('branch_id', $request->user()->branch_id)
            ->select('id', 'segment_id');

        if ($search !== '') {
            $query->where('segment_id', 'like', '%'.$search.'%');
        }

        $foCable = $query->get();

        return $foCable->map(function ($foCable) {
            return [
                'id' => $foCable->id,
                'text' => $foCable->segment_id,
            ];
        })->toArray();
    }

    public function getSelectedData(?int $foCableId): ?array
    {
        $foCable = self::where('id', $foCableId)->first();

        if ($foCable === null) {
            return null;
        }

        return [
            'id' => $foCable->id,
            'code' => $foCable->segment_id,
        ];
    }
}
