<?php

namespace App\Service\GeneralMasterData\Area;

use AllowDynamicProperties;
use App\Http\Requests\AreaRequest;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

#[AllowDynamicProperties] class AreaService
{
    private static int $perPage = 10;

    public function __construct()
    {
        $this->area = new Area();
    }

    public function data(Request $request): LengthAwarePaginator
    {
        $areaQuery = Area::with('branch', 'department')->withCount('areaHasUser')
            ->where(function ($query) use ($request) {
                if ($request->user()->hasRole('Head Engineer')) {
                    $query->whereHas('areaHasUser.user', function ($query) use ($request) {
                        $query->where('user_id', $request->user()->id);
                    });
                }
                if ($request->user()->hasRole('Branch Manager')) {
                    $query->where('branch_id', $request->user()->branch_id);
                }
            })->paginate(self::$perPage);

        return self::formattedData($areaQuery);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $area = Area::with('branch')->when(!empty($search), function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhereHas('branch', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
        })->paginate(self::$perPage);

        return self::formattedData($area);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $branchId = $request->branch_id;
        $filterQuery = Area::with('branch')
            ->when(!empty($branchId), function ($query) use ($branchId) {
                $query->whereHas('branch', function ($query) use ($branchId) {
                    $query->where('id', $branchId);
                });
            })->paginate(self::$perPage);

        return self::formattedData($filterQuery);
    }


    private static function formattedData(LengthAwarePaginator $areaQuery): LengthAwarePaginator
    {
        $data = $areaQuery->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'branch_name' => $item->branch->name,
                'department_name' => $item->department->name,
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
        return Area::create($data);
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
