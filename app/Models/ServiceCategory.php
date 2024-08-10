<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * @property int $id
 * @property string $name
 * @property int $capacity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceCategory whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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
            $query->where('capacity', 'like', '%'.$search.'%');
        }

        $serviceCategories = $query->get(['id', 'name', 'capacity']);

        return $serviceCategories->map(function ($c) {
            $nameAndCapacity = $c->name.' ('.$c->capacity.'/Mbps)';

            return [
                'id' => $c->id,
                'text' => $nameAndCapacity,
            ];
        })->toArray();
    }
}
