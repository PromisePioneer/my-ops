<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserPlacement extends Model
{
    use HasFactory;

    protected $table = 'user_placements';

    protected $fillable = [
        'code',
        'name',
    ];

    //eloquent
    public function getData(Request $request)
    {
        $search = $request->input('search');
        $data = self::orderBy('name');

        if ($search !== '') {
            $data = $data->where('name', 'like', '%'.$search.'%');
        }

        return $data->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        });
    }

    public function getSelectedData(int $placementId): array
    {
        $placement = self::where('id', $placementId)->first();

        return [
            'id' => $placement->id,
            'name' => $placement->name,
        ];
    }
}
