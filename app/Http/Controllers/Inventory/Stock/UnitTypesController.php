<?php

namespace App\Http\Controllers\Inventory\Stock;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\UnitTypeRequest;
use App\Models\UnitType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnitTypesController extends Controller
{
    private static int $perPage = 10;

    private UnitType $unitType;

    public function __construct()
    {
        $this->unitType = new UnitType();
    }

    public function index(): View
    {
        return view('pages.general-master-data.unit-types.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->unitType->getDataWithPagination(self::$perPage));
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $unitType = UnitType::when(!empty($search), function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->paginate(self::$perPage);
        return response()->json($unitType);
    }

    public function store(UnitTypeRequest $request): JsonResponse
    {
        $unitType = UnitType::create($request->validated());
        return response()->json($unitType);
    }

    public function edit(UnitType $unitType): JsonResponse
    {
        return response()->json($unitType);
    }

    public function update(UnitTypeRequest $request, UnitType $unitType): JsonResponse
    {
        return response()->json($unitType->update($request->validated()));
    }

    public function destroy(Request $request, UnitType $unitType): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $unitType->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }
}
