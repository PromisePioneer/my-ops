<?php

namespace App\Support\Master\Common\UnitType\Service;

use AllowDynamicProperties;
use App\Models\Master\Common\UnitType;
use App\Support\Master\Common\UnitType\Repository\UnitTypeRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class UnitTypeService
{


    private static int $perPage = 10;

    public function __construct()
    {
        $this->unitType = new UnitType();
        $this->unitTypeRepository = new UnitTypeRepository();
    }


    public function data(): LengthAwarePaginator
    {
        $data = $this->unitTypeRepository->data()->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $data = $this->unitType::search($search)->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function formattedData(LengthAwarePaginator $unitTypes): LengthAwarePaginator
    {
        $data = $unitTypes->getCollection()->map(function ($unitType) {
            return [
                'id' => $unitType->id,
                'name' => $unitType->name,
            ];
        });


        $unitTypes->setCollection($data);
        return $unitTypes;
    }


    public function destroy(Request $request, UnitType $unitType): bool
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        return $unitType->whereIn('id', $explodeID)->delete();
    }


    public function getUnitTypes(Request $request)
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

    public function archivedData(): LengthAwarePaginator
    {
        $data = $this->unitTypeRepository->archivedData()->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function archivedSearch(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $data = $this->unitType::search($search)->onlyTrashed()->paginate(self::$perPage);
        return self::formattedData($data);
    }

    public function restore(Request $request, UnitType $unitType): bool|int
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        return $unitType->whereIn('id', $explodeID)->restore();
    }


    /**
     * @throws Exception
     */
    public function forceDelete(Request $request, UnitType $unitType): void
    {

        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $unitTypes = $unitType->whereIn('id', $explodeID)->onlyTrashed()->get();
        foreach ($unitTypes as $unitType) {
            if ($unitType->ifRelatedDataExists($unitType)) {
                throw new Exception('Data terhubung dengan data lain, tidak bisa dihapus permanen.');
            }
            $unitType->forceDelete();
        }
    }
}

