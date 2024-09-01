<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|Department newModelQuery()
 * @method static Builder|Department newQuery()
 * @method static Builder|Department query()
 * @method static Builder|Department whereCode($value)
 * @method static Builder|Department whereCreatedAt($value)
 * @method static Builder|Department whereId($value)
 * @method static Builder|Department whereName($value)
 * @method static Builder|Department whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'code',
        'name',
    ];


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
