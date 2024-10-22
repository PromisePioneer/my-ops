<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    private static int $perPage = 10;


    public function index()
    {
        return view('pages.master.supplier.index');
    }


    public function data(): JsonResponse
    {
        $suppliers = Supplier::paginate(10);
        return response()->json($suppliers);
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $query = Supplier::orderBy('name');


        if (!empty($search)) {
            $query->where('name', 'like', '%'.$search.'%')
                ->orWhere('address', 'like', '%'.$search.'%')
                ->orWhere('phone', 'like', '%'.$search.'%');
        }

        $supplier = $query->paginate(self::$perPage);
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
