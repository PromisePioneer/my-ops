<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Laravel\Scout\Searchable;

class UnitType extends Model
{
    use Searchable;
    protected $table = 'unit_types';
    protected $fillable = [
        'name',
    ];


    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public function getData(Request $request): array
    {

        $search = $request->input('search');

        $query = self::orderby('name', 'asc')->select('id', 'name');

        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%')
                ->where('name', 'like', '%' . $search . '%');
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
