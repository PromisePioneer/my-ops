<?php

namespace App\Http\Controllers\Master\Common;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\UnitTypeRequest;
use App\Models\Master\Common\UnitType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

#[AllowDynamicProperties] class UnitTypeController extends Controller
{

    private static int $perPage = 10;

    public function index(): View
    {
        return view('pages.master.common.unit-types.index');
    }


    public function data(): JsonResponse
    {
        $data = UnitType::query()->orderBy('name')->paginate(self::$perPage);
        return response()->json($data);
    }


    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $data = UnitType::search($search)->paginate(self::$perPage);
        return response()->json($data);
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
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $unitType->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }


    public function getUnitTypes(Request $request): Collection
    {
        $search = $request->input('search');
        $unitTypes = UnitType::search($search)->get();

        return $unitTypes->map(function ($unitType) {
            return [
                'id' => $unitType->id,
                'text' => $unitType->name
            ];
        });
    }


    public function selectedUnitType(UnitType $unitType): array
    {
        return [
            'id' => $unitType->id,
            'name' => $unitType->name
        ];
    }
}
