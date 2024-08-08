<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Branch extends Model
{
    protected $table = 'branches';

    protected $fillable = [
        'name',
        'code',
    ];

    //eloquent
    public function getData(Request $request): array
    {
        $search = $request->search;
        $query = self::orderby('name', 'asc')->select('id', 'name', 'code');

        if ($search !== '') {
            $query->where('name', 'like', '%'.$search.'%');
        }

        $branches = $query->get();

        return $branches->map(function ($c) {
            return [
                'id' => $c->id,
                'text' => $c->name,
            ];
        })->toArray();
    }

    public function getSelectedData(?int $branchId = null): array|null
    {
        $branch = self::where('id', $branchId)->first();
        if ($branchId === null) {
            return null;
        }
        return [
            'id' => $branch->id,
            'code' => $branch->code,
            'name' => $branch->name,
        ];
    }
}
