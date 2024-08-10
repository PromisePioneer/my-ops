<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UnitType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitType query()
 * @method static \Illuminate\Database\Eloquent\Builder|UnitType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UnitType whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class UnitType extends Model
{
    protected $table = 'unit_types';

    protected $fillable = [
        'name',
    ];

    //eloquent
    public function getDataWithPagination(int $perPage): LengthAwarePaginator
    {
        return self::orderBy('name')->paginate($perPage);
    }

    public function searchData(Request $request)
    {
        $search = $request->input('search');

        return self::where('name', 'like', '%'.$search.'%')->get();
    }

    public function getData(Request $request): array
    {

        $search = $request->input('search');

        $query = self::orderby('name', 'asc')->select('id', 'name');

        if ($search !== '') {
            $query->where('name', 'like', '%'.$search.'%')
                ->where('name', 'like', '%'.$search.'%');
        }

        return $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        })->toArray();
    }

    public function getSelectedData(int $unitTypeId): array
    {
        $unitType = self::where('id', $unitTypeId)->first();

        return [
            'id' => $unitType->id,
            'name' => $unitType->name,
        ];
    }
}
