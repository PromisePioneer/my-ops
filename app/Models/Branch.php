<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Branch extends Model
{
    protected $table = 'branches';
    protected $fillable = [
        'name',
        'code'
    ];

    //eloquent
    public function getData(Request $request): array
    {
        $search = $request->search;
        $query = self::orderby('name', 'asc')->select('id', 'name');

        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $branches = $query->get();

        return $branches->map(function ($c) {
            return [
                "id" => $c->id,
                "text" => $c->name
            ];
        })->toArray();
    }

    public function getSelectedData($branchId): array
    {
        $branch = self::where('id', $branchId)->first();
        return array(
            "id" => $branch->id,
            "name" => $branch->name
        );
    }
}
