<?php

namespace App\Service\UserAllowance\PositionAllowance;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PositionAllowanceService
{
    private static int $perPage = 10;

    public function data(): LengthAwarePaginator
    {
        $query = User::with('jobInformation', 'roles')
            ->paginate(self::$perPage);

        return self::formattedData($query);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search ');
        $query = User::search($search)->query(callback: function ($query) {
            $query->with('jobInformation');
        })->paginate(self::$perPage);

        return self::formattedData($query);
    }

    private static function formattedData(LengthAwarePaginator $data): LengthAwarePaginator
    {
        $jobInformation = $data->getCollection()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'nip' => $user->nip,
                'position_allowance' => 'Rp.' . number_format((float)$user->jobInformation?->position_allowance) ?? '-',
                'role_name' => $user->jobInformation?->roles?->pluck('name')->implode(', ') ?? '-',
            ];
        });

        $data->setCollection($jobInformation);
        return $data;
    }

    public function filter(Request $request): LengthAwarePaginator
    {
        $query = User::with('jobInformation');
        $filter = PositionAllowanceQueryFilter::apply($query, $request)
            ->paginate(self::$perPage);

        return self::formattedData($filter);
    }


}
