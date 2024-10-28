<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ServiceCategory extends Model
{
    protected $table = 'services_categories';

    protected $fillable = [
        'name',
        'unit_price',
        'capacity',
    ];

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
}
