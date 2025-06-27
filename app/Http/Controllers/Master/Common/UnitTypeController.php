<?php

namespace App\Http\Controllers\Master\Common;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\UnitTypeRequest;
use App\Models\Master\Common\UnitType;
use App\Support\Master\Common\UnitType\Service\UnitTypeService;
use Carbon\Unit;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

#[AllowDynamicProperties] class UnitTypeController extends Controller
{


    public function __construct()
    {
        $this->unitTypeService = new UnitTypeService();
    }

    public function index(): View
    {
        return view('pages.master.common.unit-types.index');
    }


    public function data(): JsonResponse
    {

        return response()->json($this->unitTypeService->data());
    }


    public function search(Request $request): JsonResponse
    {
        $this->unitTypeService->search($request);
        return response()->json();
    }

    public function store(UnitTypeRequest $request): JsonResponse
    {
        UnitType::create($request->validated());
        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }


    public function edit(UnitType $unitType): JsonResponse
    {
        return response()->json($unitType);
    }


    public function update(UnitTypeRequest $request, UnitType $unitType): JsonResponse
    {
        $unitType->update($request->validated());
        return response()->json([
            'message' => 'Data berhasil disimpan'
        ]);
    }


    public function destroy(Request $request, UnitType $unitType): JsonResponse
    {
        $this->unitTypeService->destroy($request, $unitType);
        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }


    public function getUnitTypes(Request $request): JsonResponse
    {
        return response()->json($this->unitTypeService->getUnitTypes($request));
    }


    public function selectedUnitType(UnitType $unitType): array
    {
        return [
            'id' => $unitType->id,
            'name' => $unitType->name
        ];
    }


    public function archives()
    {
        return view('pages.master.common.unit-types.archives');
    }

    public function archivedData(): JsonResponse
    {
        return response()->json($this->unitTypeService->archivedData());
    }

    public function archivedSearch(Request $request)
    {

        return response()->json($this->unitTypeService->archivedSearch($request));
    }

    public function restore(Request $request, UnitType $unitType): JsonResponse
    {
        $this->unitTypeService->restore($request, $unitType);
        return response()->json(['message' => 'Data berhasil dipulihkan']);
    }

    /**
     * @throws Exception
     */
    public function forceDelete(Request $request, UnitType $unitType): JsonResponse
    {
        $this->unitTypeService->forceDelete($request, $unitType);
        return response()->json(['message' => 'Data berhasil dihapus permanen']);
    }
}
