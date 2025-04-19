<?php

namespace App\Support\Master\Common\Area;

use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AreaDetailService
{
    private static int $perPage = 10;

    public function data(Area $area): LengthAwarePaginator
    {
        $userHasAreaQuery = User::with('roles', 'jobInformation', 'userHasArea', 'weekHoliday')
            ->whereHas('userHasArea', function ($query) use ($area) {
                $query->where('area_id', $area->id);
            })->paginate(self::$perPage);

        return self::formattedData($userHasAreaQuery);
    }


    public function search(Request $request, Area $area): LengthAwarePaginator
    {
        $search = $request->input('search');
        $searchQuery = User::search($search)->query(callback: function ($query) use ($area) {
            $query->whereHas('userHasArea', function ($query) use ($area) {
                $query->where('area_id', $area->id);
            });
        })->paginate(self::$perPage);
        return self::formattedData($searchQuery);
    }


    public function formattedData(LengthAwarePaginator $userHasAreaQuery): LengthAwarePaginator
    {
        $data = $userHasAreaQuery->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'user_name' => "({$item->nip}) {$item->name}",
                'role_name' => $item->roles->pluck('name'),
                'week_holiday' => $item->weekHoliday?->day,
            ];
        });

        $userHasAreaQuery->setCollection($data);
        return $userHasAreaQuery;
    }


    public function getUser(Request $request, Area $area): array
    {
        $search = $request->search;
        $query = User::with('roles')->whereDoesntHave('userHasArea')
            ->whereHas('roles', function ($query) use ($area) {
                $query->whereIn('name', ['Head Engineer', 'Engineer', 'Senior Engineer', 'Vendor', 'KU Head Engineer', 'KU Engineer']);
            })
            ->where('branch_id', $area->branch_id)
            ->where('active', 1)
            ->orderBy('name')
            ->select('id', 'name', 'nip');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nip', 'like', '%' . $search . '%');
            });
        }

        $users = $query->get();

        return $users->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => '(' . $item->nip . ')' . ' ' . '(' . $item->roles->pluck('name')->implode(', ') . ')' . ' ' . $item->name,
            ];
        })->toArray();
    }

}
