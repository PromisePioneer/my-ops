<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name',
        'code',
        'category',
        'unit_price'
    ];


    public function getDataWithPagination(int $perPage): LengthAwarePaginator
    {
        $product = self::orderBy('name', 'ASC')->paginate($perPage);
        return self::formattedData($product);
    }


    public function searchData(Request $request, int $perPage): LengthAwarePaginator
    {
        $search = $request->input('search');
        $searchQuery = self::where('name', 'like', '%' . $search . '%')
            ->orWhere('code', 'like', '%' . $search . '%')
            ->orWhere('category', 'like', '%' . $search . '%')
            ->orWhere('unit_price', 'like', '%' . $search . '%')
            ->limit(25)
            ->paginate($perPage);

        return self::formattedData($searchQuery);
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



}
