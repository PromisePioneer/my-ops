<?php

namespace App\Http\Controllers\Inventory\Stock;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\UnitTypeRequest;
use App\Models\UnitType;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

#[AllowDynamicProperties] class UnitTypeController extends Controller
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->unitType = new UnitType();
    }


    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', UnitType::class);
        return view('pages.general-master-data.unit-types.index');
    }


    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', UnitType::class);
        $unitTypes = UnitType::orderBy('name')->paginate(self::$perPage);
        return response()->json($unitTypes);
    }


    /**
     * @throws AuthorizationException
     */
    public function search(Request $request): JsonResponse
    {
        $this->authorize('view', UnitType::class);
        $search = $request->input('search');
        $unitTypes = UnitType::search($search)->query(function ($query) {
            $query->orderBy('name');
        })->paginate(self::$perPage);

        return response()->json($unitTypes);
    }


    /**
     * @throws AuthorizationException
     */
    public function store(UnitTypeRequest $request): JsonResponse
    {
        $this->authorize('create', UnitType::class);
        $unitType = UnitType::create($request->validated());
        return response()->json($unitType);
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(UnitType $unitType): JsonResponse
    {
        $this->authorize('update', $unitType);
        return response()->json($unitType);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UnitTypeRequest $request, UnitType $unitType): JsonResponse
    {
        $this->authorize('update', $unitType);
        return response()->json($unitType->update($request->validated()));
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request, UnitType $unitType): JsonResponse
    {
        $this->authorize('delete', $unitType);
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $unitType->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }


    public function getUnitTypes(Request $request): array
    {
        $search = $request->input('search');
        $query = UnitType::search($search)->query(function ($query) {
            $query->orderBy('name');
        })->get();

        return $query->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        })->toArray();
    }

    public function selectedUnitType(UnitType $unitType): array
    {
        $unitType = UnitType::where('id', $unitType->id)->first();

        return [
            'id' => $unitType->id,
            'name' => $unitType->name,
        ];
    }


}
