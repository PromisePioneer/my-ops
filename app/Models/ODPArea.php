<?php

namespace App\Models;

use App\Models\Master\Common\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class ODPArea extends Model
{
    use HasFactory;

    protected $table = 'odp_areas';
    protected $fillable = [
        'branch_id',
        'code',
    ];


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::with('branch')->orderby('code')->select('id', 'code');

        if ($search !== '') {
            $query->where('code', 'like', '%'.$search.'%');
        }

        $odpArea = $query->get();
        return $odpArea->map(function ($c) {
            return [
                'id' => $c->id,
                'text' => $c->branch->name ?? 'Pusat'.' - '.$c->code,
            ];
        })->toArray();
    }

    public function getSelectedData(int $areaId): array
    {
        $area = self::where('id', $areaId)->first();
        return [
            'id' => $area->id,
            'code' => $area->code,
        ];
    }
}
