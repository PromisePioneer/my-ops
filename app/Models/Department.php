<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'code',
        'name',
    ];

    public function getData(Request $request): array
    {
        $search = $request->input('search');
        $department = self::orderby('name', 'asc');
        if ($search !== '') {
            $department->where('name', 'like', '%'.$search.'%');
        }

        return $department->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        })->toArray();
    }
}
