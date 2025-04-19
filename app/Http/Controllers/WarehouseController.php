<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\Models\Warehouse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    private static int $perPage = 10;

    public function index(): View
    {
        return view('pages.operational-master-data.warehouses.index');
    }

    public function data(): JsonResponse
    {
        $warehouses = Warehouse::paginate(self::$perPage);
        return response()->json($warehouses);
    }


    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $warehouses = Warehouse::when(!empty($search), function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
            $query->where('code', 'like', '%' . $search . '%');
        })->paginate(10);

        return response()->json($warehouses);
    }


    public function store(WarehouseRequest $request): JsonResponse
    {
        Warehouse::create($request->validated());
        return response()->json(['message' => 'data berhasil disimpan']);
    }


    public function edit(Warehouse $warehouse): JsonResponse
    {
        return response()->json($warehouse);
    }

    public function update(Warehouse $warehouse, WarehouseRequest $request): JsonResponse
    {
        $warehouse->update($request->validated());
        return response()->json(['message' => 'data berhasil disimpan']);
    }


    public function destroy(Warehouse $warehouse, Request $request): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $warehouse->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }
}
