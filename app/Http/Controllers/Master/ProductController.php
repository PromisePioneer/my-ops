<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Product\ProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public int $perPage = 10;

    private Product $product;

    public function __construct()
    {
        $this->middleware('permission:lihat product', ['only' => ['index']]);
        $this->middleware('permission:tambah product', ['only' => ['create', 'store']]);
        $this->middleware('permission:update product', ['only' => ['edit', 'update']]);
        $this->middleware('permission:hapus product', ['only' => ['destroy']]);

        $this->product = new Product();
    }

    public function index(): View
    {
        return view('pages.master.product.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->product->getDataWithPagination($this->perPage));
    }

    public function search(Request $request): JsonResponse
    {

        return response()->json($this->product->searchData($request, $this->perPage));
    }

    public function store(ProductRequest $request): JsonResponse
    {
        Product::create($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ], 200);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json($product);
    }

    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ], 200);
    }

    /**
     * @throws \Exception
     */
    public function destroy(Request $request, Product $product): JsonResponse
    {

        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $product->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 204);
    }
}
