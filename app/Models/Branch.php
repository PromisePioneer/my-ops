<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Branch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch query()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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

    public function getSelectedData(?int $branchId = null): ?array
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
