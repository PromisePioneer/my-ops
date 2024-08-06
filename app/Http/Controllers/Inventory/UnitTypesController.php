<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\UnitTypeRequest;
use App\Models\UnitType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnitTypesController extends Controller
{
    public int $perPage = 10;

    private UnitType $unitType;

    public function __construct()
    {
        $this->unitType = new UnitType;
    }

    public function index(): View
    {
        return view('pages.inventory.unit-types.index');
    }

    public function data(): JsonResponse
    {
        return response()->json($this->unitType->getDataWithPagination($this->perPage));
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->unitType->searchData($request));
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

    public function destroy(UnitType $unitType): JsonResponse
    {
        return response()->json($unitType->delete());
    }
}
