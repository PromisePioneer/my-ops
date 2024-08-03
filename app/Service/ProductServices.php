<?php

namespace App\Service;

use App\Models\Product;

class ProductServices
{
    public function getProductData($request): array
    {
        if ($request->search === '') {
            $product = Product::orderby('name', 'asc')
                ->select('id', 'name', 'unit_price')
                ->limit(5)
                ->get();
        } else {
            $product = Product::orderby('name', 'asc')
                ->select('id', 'name', 'unit_price')
                ->where('name', 'like', '%' . $request->search . '%')
                ->limit(5)
                ->get();
        }

        $response = array();
        foreach ($product as $c) {
            $response[] = array(
                "id" => $c->id,
                "text" => $c->name,
                "price" => $c->unit_price
            );
        }

        return $response;
    }
}
