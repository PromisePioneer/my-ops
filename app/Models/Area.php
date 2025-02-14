<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class Area extends Model
{
    protected $table = 'areas';
    protected $fillable = [
        'department_id',
        'branch_id',
        'name',
    ];


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }


    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }


    public function areaHasUser(): HasMany
    {
        return $this->hasMany(UserHasArea::class, 'area_id');
    }


    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $query = self::with('branch')
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->orderby('name', 'asc');

        return $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->branch->name . ' - ' . $item->name,
            ];
        })->toArray();
    }


}
