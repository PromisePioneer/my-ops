<?php

namespace App\Http\Controllers\Master\Operational;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Operational\Supplier\SupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    private static int $perPage = 10;

    public function index(): View
    {
        return view('pages.master.operational.supplier.index');
    }


    public function data(): JsonResponse
    {
        $suppliers = Supplier::query()->orderBy('name')->paginate(10);
        return response()->json($suppliers);
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $supplier = Supplier::when(!empty($search), function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->orderBy('name')->paginate(self::$perPage);

        return response()->json($supplier);
    }

    public function store(SupplierRequest $request): JsonResponse
    {
        return response()->json(Supplier::create($request->validated()));
    }

    public function edit(Supplier $supplier): JsonResponse
    {
        return response()->json($supplier);
    }

    public function update(SupplierRequest $request, Supplier $supplier): JsonResponse
    {
        return response()->json($supplier->update($request->validated()));
    }


    public function destroy(Request $request, Supplier $supplier): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $supplier->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }
}
