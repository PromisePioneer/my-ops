<?php

namespace App\Http\Controllers\Master\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Product\ProductRequest;
use App\Models\Product;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public int $perPage = 10;

    private Product $product;

    public function __construct()
    {
        $this->product = new Product();
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', Product::class);

        return view('pages.general-master-data.product.index');
    }

    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', Product::class);

        return response()->json($this->product->getDataWithPagination($this->perPage));
    }

    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', Product::class);

        return response()->json($this->product->searchData($request, $this->perPage));
    }

    /**
     * @throws AuthorizationException
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $this->authorize('create', Product::class);
        Product::create($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ], 200);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Product $product): JsonResponse
    {
        $this->authorize('update produk', $product);

        return response()->json($product);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        $this->authorize('update produk', $product);
        $product->update($request->validated());

        return response()->json([
            'message' => 'data berhasil disimpan',
        ], 200);
    }

    /**
     * @throws Exception
     */
    public function destroy(Request $request, Product $product): JsonResponse
    {
        $this->authorize('delete', $product);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $product->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 204);
    }
}
