<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Laravel\Scout\Searchable;

class Department extends Model
{
    use HasFactory, Searchable;

    protected $table = 'departments';

    protected $fillable = [
        'code',
        'name',
    ];

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->code,
            'name' => $this->name
        ];
    }

    public function departments(): HasMany
    {
        return $this->hasMany(RoleHasDepartment::class, 'department_id');
    }

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

    public function getSelectedData(?int $departmentId): array
    {
        $department = self::where('id', $departmentId)->first();

        return [
            'id' => $department?->id ?? '-',
            'name' => $department?->name ?? '-',
        ];
    }
}
