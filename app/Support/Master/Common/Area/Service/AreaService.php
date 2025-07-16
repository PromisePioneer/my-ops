<?php

namespace App\Support\Master\Common\Area\Service;

use AllowDynamicProperties;
use App\Http\Requests\Master\Common\Area\AreaRequest;
use App\Models\Area;
use App\Support\Master\Common\Area\Respository\AreaRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class AreaService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->areaRepository = new AreaRepository();
        $this->area = new Area();
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $query = $this->areaRepository->getData();
        $aclFilter = AreaACLQueryService::apply($query, $request)->paginate(self::$perPage);
        return self::formattedData($aclFilter);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');


        $area = $this->area->search($search)->query(function ($query) use ($request) {
            return AreaACLQueryService::apply($this->areaRepository->searchAreaQuery($query), $request);
        })->paginate(self::$perPage);

        return self::formattedData($area);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $query = Area::with('branch');
        $areaFilterQuery = AreaFilterQueryService::apply($query, $request);
        $aclFilterQuery = AreaACLQueryService::apply($areaFilterQuery, $request)
            ->paginate(self::$perPage);

        return self::formattedData($aclFilterQuery);
    }


    private static function formattedData(LengthAwarePaginator $areaQuery): LengthAwarePaginator
    {
        $data = $areaQuery->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'branch_name' => $item->branch?->name,
                'department_name' => $item->department?->name,
                'area_name' => $item->name,
                'total_user' => $item->areaHasUser->count(),
            ];
        });

        $areaQuery->setCollection($data);
        return $areaQuery;
    }

    public function store(AreaRequest $request)
    {
        $data = $request->validated();
        $data['branch_id'] = $request->user()->branch_id
            ? $request->user()->branch_id
            : $request->branch_id;
        return $this->area->create($data);
    }


    public function update(AreaRequest $request, Area $area): bool
    {
        $data = $request->validated();
        $data['branch_id'] = $request->user()->branch_id
            ? $request->user()->branch_id
            : $request->branch_id;
        return $area->update($data);
    }


}
