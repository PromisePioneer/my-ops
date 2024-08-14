<?php

namespace App\Models;

use Eloquent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $category
 * @property float $unit_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product whereCategory($value)
 * @method static Builder|Product whereCode($value)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereName($value)
 * @method static Builder|Product whereUnitPrice($value)
 * @method static Builder|Product whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'code',
        'category',
        'unit_price',
    ];

    public function getDataWithPagination(int $perPage): LengthAwarePaginator
    {
        $product = self::orderBy('name', 'ASC')->paginate($perPage);

        return self::formattedData($product);
    }

    private static function formattedData(LengthAwarePaginator $product): LengthAwarePaginator
    {
        $formattedProduct = $product->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'category' => $item->category,
                'unit_price' => number_format($item->unit_price),
            ];
        });

        $product->setCollection($formattedProduct);

        return $product;
    }

    public function searchData(Request $request, int $perPage): LengthAwarePaginator
    {
        $search = $request->input('search');
        $searchQuery = self::where('name', 'like', '%'.$search.'%')
            ->orWhere('code', 'like', '%'.$search.'%')
            ->orWhere('category', 'like', '%'.$search.'%')
            ->orWhere('unit_price', 'like', '%'.$search.'%')
            ->limit(25)
            ->paginate($perPage);

        return self::formattedData($searchQuery);
    }
}
