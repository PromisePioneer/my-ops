<?php

namespace App\Http\Requests;

use App\Models\LeaveAndPermission;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AttendanceManualFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(Request $request): array
    {
        return [
            'date' => [
                'required',
                $this->isHasLeaves($request),
            ],
            'reason' => ['required'],
            'users' => ['required'],
            'attachment.*' => ['required', 'mimes:jpg,png,jpeg'],
        ];
    }


    public function isHasLeaves(Request $request): Closure
    {
        return static function ($attribute, $value, $fail) use ($request) {
            $date = explode('to', $request->date);
            $startDate = Carbon::make($date[0]);
            $endDate = Carbon::make($date[sizeof($date) - 1]);
            $leaveAndPermission = LeaveAndPermission::where('confirmation_status', 'Diterima')
                ->whereIn('user_id', $request->users)
                ->where(function ($query) use ($request, $startDate, $endDate) {

                    $query->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate]);
                })->get();

            $leavePeriods = [];
            foreach ($leaveAndPermission as $dates) {
                $leavePeriods = array_merge(
                    $leavePeriods,
                    CarbonPeriod::create($dates->start_date, $dates->end_date)->toArray()
                );
            }


            $leaves = [];
            foreach ($leavePeriods as $date) {
                $formattedDate = Carbon::parse($date)->format('Y-m-d');
                $leaves[$formattedDate] = collect([
                    'date' => $formattedDate,
                ]);
            }

            foreach ($leaves as $leavesDate) {
                if (Carbon::parse($leavesDate['date'])->between($startDate, $endDate)) {
                    $fail('Salah satu karyawan anda memiliki izin/cuti/sakit pada tanggal ' . Carbon::parse($date)->format('d/m/Y')) . '. Silahkan pilih tanggal lain atau hapus karyawan yang memiliki cuti/izin/sakit pada tanggal tersebut';
                }
            }
        };
    }

}
