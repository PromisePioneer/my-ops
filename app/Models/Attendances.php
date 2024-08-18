<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class Attendances extends Model
{
    use HasFactory;

    protected $table = 'attendances';
    protected $fillable = [
        'sn',
        'table',
        'stamp',
        'employee_id',
        'timestamp',
        'status1',
    ];

    private static function formattedData(LengthAwarePaginator $data): Collection
    {
        return $data->groupBy(function ($item) {
            return Carbon::parse($item->timestamp)->format('Y-m-d').'-'.$item->status1;
        })->map(function ($group) {
            return [
                'date' => Carbon::parse($group->first()->timestamp)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y'),
                'records' => $group->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'sn' => $item->sn,
                        'user_name' => $item->name,
                        'employee_id' => $item->employee_id,
                        'timestamp' => Carbon::parse($item->timestamp)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('H:i'),
                        'check_in' => $item->status1,
                        'status2' => $item->status2,
                        'status3' => $item->status3,
                        'status4' => $item->status4,
                        'status5' => $item->status5,
                    ];
                }),
            ];
        })->values();
    }

    public function getAttendanceWithPagination(int $perPage): LengthAwarePaginator
    {
        $attendances = self::select('attendances.employee_id', 'users.name', 'attendances.timestamp',
            'attendances.status1')
            ->join('users', 'users.absent_id', '=', 'attendances.employee_id')
            ->leftJoin('user_shifts', 'user_shifts.user_id', '=', 'users.id')
            ->leftJoin('manage_shift', 'manage_shift.id', '=', 'user_shifts.shift_id')
            ->orderBy('attendances.timestamp', 'DESC')
            ->select('users.name as user_name', 'attendances.timestamp', 'attendances.status1',
                'manage_shift.name as shift_name', 'attendances.employee_id')
            ->get()
            ->groupBy(function ($item) {
                return $item->employee_id.'-'.Carbon::parse($item->timestamp)->format('Y-m-d');
            });

        $formattedData = $this->formatGroupedData($attendances);
        $paginator = new LengthAwarePaginator($formattedData->forPage(Paginator::resolveCurrentPage(), $perPage),
            $formattedData->count(), $perPage);

        // Adjust the pagination URL
        $paginator->withPath(url('/adms/attendances/data'));

        return $paginator;
    }

    private function formatGroupedData($groupedData)
    {
        return $groupedData->map(function ($items) {
            $checkIn = $items->where('status1', 0)->first();
            $checkOut = $items->where('status1', 1)->first();

            return [
                'name' => $checkIn ? $checkIn->user_name : $checkOut->user_name,
                'work_time' => $checkIn ? $checkIn->shift_name : $checkOut->shift_name ?? 'Default',
                'date' => $checkIn ? Carbon::parse($checkIn->timestamp)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y') : null,
                'employee_id' => $checkIn ? $checkIn->employee_id : $checkOut->employee_id,
                'checkin_time' => $checkIn ? Carbon::parse($checkIn->timestamp)->format('g:i A') : null,
                'checkout_time' => $checkOut ? Carbon::parse($checkOut->timestamp)->format('g:i A') : null,
            ];
        })->values();
    }


    public function getAttendancesDataBasedOnUserId(int $perPage)
    {
        return self::join('users', 'users.absent_id', '=', 'attendances.employee_id')
            ->paginate($perPage);
    }

}
