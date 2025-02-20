<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Laravel\Scout\Searchable;

class ServiceCategory extends Model
{
    use Searchable;
    protected $table = 'services_categories';
    protected $fillable = [
        'name',
        'unit_price',
        'capacity',
    ];


    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }

    //eloquent
    public function getData(Request $request): array
    {
        $search = $request->search;
        $query = self::orderby('name', 'asc');

        if ($search !== '') {
            $query->where('name', 'like', '%'.$search.'%');
        }

        $serviceCategories = $query->get(['id', 'name']);

        return $serviceCategories->map(function ($c) {
            $nameAndCapacity = $c->name;

            return [
                'id' => $c->id,
                'text' => $nameAndCapacity,
            ];
        })->toArray();
    }


    public function selectedData($serviceCategoryId): array
    {
        $query = self::orderby('name', 'asc')->where('id', $serviceCategoryId)->first();
        return [
            'id' => $query->id,
            'name' => $query->name
        ];
    }
}
