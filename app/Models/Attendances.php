<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
                        'timestamp' => Carbon::parse($item->timestamp)
                            ->locale('id')
                            ->settings(['formatFunction' => 'translatedFormat'])
                            ->format('H:i'),
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
        // Mengambil data dengan pagination langsung
        $query = self::select('attendances.employee_id', 'users.name as user_name', 'attendances.timestamp',
            'attendances.status1', 'work_time.name as work_time')
            ->leftjoin('users', 'users.absent_id', '=', 'attendances.employee_id')
            ->leftJoin('user_work_time', 'user_work_time.user_id', '=', 'users.id')
            ->leftJoin('work_time', 'work_time.id', '=', 'user_work_time.work_time_id')
            ->orderBy('attendances.timestamp', 'DESC');

        // Lakukan pagination sebelum grouping
        $paginator = $query->paginate($perPage);

        // Lakukan grouping setelah data diambil untuk page tertentu
        $groupedData = $paginator->getCollection()->groupBy(function ($item) {
            return $item->employee_id.'-'.Carbon::parse($item->timestamp)->format('Y-m-d');
        });

        // Format data yang telah digroup
        $formattedData = $this->formatGroupedData($groupedData);

        // Update paginator dengan data yang telah diformat
        $paginator->setCollection($formattedData);

        // Kembalikan paginator yang telah diformat
        return $paginator->withPath(url('/adms/attendances/data'));
    }

    private function formatGroupedData($groupedData)
    {
        return $groupedData->map(function ($items) {
            $checkIn = $items->where('status1', 0)->first();
            $checkOut = $items->where('status1', 1)->last();

            $userWorktime = WorkTime::where('name', $checkIn->work_time)->first();
            $defaultWorkTime = WorkTime::where('id', 1)->first();
            $expectedCheckInTime = $userWorktime ? $userWorktime->clock_in : $defaultWorkTime->clock_in;

            // Combine the date of check-in with the expected time
            $expectedCheckIn = Carbon::parse($checkIn->timestamp)->format('Y-m-d').' '.$expectedCheckInTime;
            $expectedCheckIn = Carbon::parse($expectedCheckIn);

            // Calculate lateness in minutes
            $actualCheckIn = Carbon::parse($checkIn->timestamp);
            $minutesLate = $actualCheckIn->greaterThan($expectedCheckIn) ? $expectedCheckIn->diffInMinutes($actualCheckIn) : 0;

            return [
                'name' => $checkIn->user_name,
                'work_time' => $userWorktime ? $userWorktime->name : $defaultWorkTime->name,
                'date' => Carbon::parse($checkIn->timestamp)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y'),
                'employee_id' => $checkIn->employee_id,
                'checkin_time' => $actualCheckIn->format('H:i'),
                'late_checkin' => (int) $minutesLate,
                'checkout_time' => $checkOut ? Carbon::parse($checkOut->timestamp)->format('H:i') : null,
            ];
        })->filter()->values(); // Tambahkan filter() untuk menghapus null values
    }


    public function getAttendancesDataBasedOnUserId(int $perPage, int $absentId): LengthAwarePaginator
    {
        $query = self::join('users', 'users.absent_id', '=',
            'attendances.employee_id')->where('attendances.employee_id', $absentId)->paginate($perPage);

        self::formattedAbsentDataBasedOnUserId($query);
        return $query;
    }


    private static function formattedAbsentDataBasedOnUserId(LengthAwarePaginator $attedancesData): void
    {
        $formattedAttendances = $attedancesData->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'timestamp' => Carbon::parse($item->timestamp)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->format('l, j F Y H:i:s'),
                'status1' => $item->status1,
            ];
        });

        $attedancesData->setCollection($formattedAttendances);
    }


    public function getAttendancesPeriod(int $perPage): LengthAwarePaginator
    {
        return self::selectRaw("CONCAT(MONTH(timestamp), '-', YEAR(timestamp)) as waktu")->distinct()->paginate($perPage);
    }


    public function getAttendancesBasedOnPeriod(string $month, string $year, int $perPage): LengthAwarePaginator
    {
        $attendances = self::join('users', 'users.absent_id', '=', 'attendances.employee_id')
            ->leftJoin('branches', 'branches.id', '=', 'users.branch_id')
            ->leftJoin('user_work_time', 'user_work_time.user_id', '=', 'users.id')
            ->leftJoin('work_time', 'work_time.id', '=', 'user_work_time.work_time_id')
            ->whereMonth('attendances.timestamp', $month)
            ->whereYear('attendances.timestamp', $year)
            ->orderBy('attendances.timestamp', 'DESC')
            ->select('users.nip as user_nip', 'branches.name as branches_name', 'users.name as user_name',
                'attendances.timestamp',
                'attendances.status1',
                'work_time.name as work_time', 'attendances.employee_id')
            ->get()
            ->groupBy(function ($item) {
                return $item->user_name;
            });

        $formattedData = $this->formatGroupedDataForAttendancesSummary($attendances, $month, $year);
        $paginator = new LengthAwarePaginator($formattedData->forPage(Paginator::resolveCurrentPage(), $perPage),
            $formattedData->count(), $perPage);

        $paginator->withPath(url("adms/attendances-summary/detail/data/01-{$month}-{$year}"));

        return $paginator;
    }

    private function formatGroupedDataForAttendancesSummary($attendances, $month, $year)
    {
        return $attendances->map(function ($items) use ($month, $year) {
            $totalMinutesLate = 0;

            // Group by each day to calculate daily lateness
            $dailyAttendances = $items->groupBy(function ($item) {
                return Carbon::parse($item->timestamp)->format('Y-m-d');
            });

            foreach ($dailyAttendances as $day => $dailyItems) {
                $checkIn = $dailyItems->where('status1', 0)->first();
                $userWorktime = WorkTime::where('name', $checkIn?->work_time)->first();
                $defaultWorkTime = WorkTime::where('id', 1)->first();
                $expectedCheckInTime = $userWorktime ? $userWorktime->clock_in : $defaultWorkTime->clock_in;

                // Combine the date of check-in with the expected time
                $expectedCheckIn = Carbon::parse($checkIn?->timestamp)->format('Y-m-d').' '.$expectedCheckInTime;
                $expectedCheckIn = Carbon::parse($expectedCheckIn);

                // Calculate lateness in minutes for that day
                $actualCheckIn = Carbon::parse($checkIn->timestamp);
                if ($actualCheckIn->greaterThan($expectedCheckIn)) {
                    $minutesLate = $expectedCheckIn->diffInMinutes($actualCheckIn);
                    $totalMinutesLate += $minutesLate;
                }
            }

            $totalPresent = $items->where('status1', 0)->count();

            return [
                'branch' => $items->first()->branch_name ?? 'Pusat',
                'nip' => $items->first()->user_nip,
                'name' => $items->first()->user_name,
                'total_hadir' => $totalPresent,
                'total_menit_terlambat' => (int) $totalMinutesLate
            ];
        })->values();
    }

    public function searchAttendancesSummary(Request $request)
    {
        // Ambil input pencarian dari query string
        $search = $request->input('search');

        // Mulai query dengan filter berdasarkan input pencarian
        $query = self::join('users', 'users.absent_id', '=', 'attendances.employee_id')
            ->leftJoin('branches', 'branches.id', '=', 'users.branch_id')
            ->leftJoin('user_work_time', 'user_work_time.user_id', '=', 'users.id')
            ->leftJoin('work_time', 'work_time.id', '=', 'user_work_time.work_time_id')
            ->select('users.nip as user_nip', 'users.name as user_name', 'attendances.timestamp', 'attendances.status1',
                'work_time.name as work_time', 'attendances.employee_id');

        // Terapkan filter pencarian
        if ($search) {
            $query->where('users.name', 'like', '%'.$search.'%')
                ->orWhere('branches.name', 'like', '%'.$search.'%')
                ->orWhere('work_time.name', 'like', '%'.$search.'%')
                ->orWhere('users.nip', 'like', '%'.$search.'%');
        }

        // Ambil hasil pencarian
        $attendances = $query->orderBy('attendances.timestamp', 'DESC')->get();

        // Format data sebelum dikirim ke frontend
        $groupedAttendances = $attendances->groupBy(function ($item) {
            return $item->user_name;
        });

        return $this->formatGroupedDataForAttendancesSummary($groupedAttendances, null, null);
    }

}
