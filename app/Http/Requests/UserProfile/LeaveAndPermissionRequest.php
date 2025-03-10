<?php

namespace App\Http\Requests\UserProfile;

use App\Models\EmployeeSchedule;
use App\Models\LeaveAndPermission;
use App\Models\User;
use App\Models\WeekHoliday;
use App\Service\User\LeaveAndPermission\CalculateUserLeaves;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeaveAndPermissionRequest extends FormRequest
{
    private CalculateUserLeaves $calculateUserLeaves;

    public function __construct()
    {
        parent::__construct();
        $this->calculateUserLeaves = new CalculateUserLeaves();
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(Request $request): array
    {
        $getStartDate = $this->getStartDate($request);
        $getLatestDate = $this->getLatestDate($request);
        $getDiproses = $this->getDiproses($request);

        $getLeavesDaysInThisMonth = $this->calculateLeaveDaysInThisMonth($getStartDate, $getLatestDate);
        $getDiffDaysBetweenStartDateAndEndDate = $this->calculateDiffDays($request->start_date, $request->end_date);
        $ifDateRangeHasWeekHoliday = $this->ifDateRangeHasWeekHoliday($request);
        $ifDateRangeHasLeaves = $this->ifDateRangeHasLeaves($request);

        return [
            'start_date' => [
                'required',
                'date',
                $this->validateStartDate($request, $getDiproses),
                $ifDateRangeHasWeekHoliday,
                $ifDateRangeHasLeaves
            ],
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
                $this->validateEndDate($request, $getLeavesDaysInThisMonth, $getDiffDaysBetweenStartDateAndEndDate),
            ],
            'reason' => ['required'],
            'leaves_status' => ['required'],
            'sick_letter' => [
                Rule::requiredIf(fn() => $request->leaves_status === 'Sakit'),
                'mimes:jpg,png,jpeg',
                'max:2048',
            ],
        ];
    }

    /**
     * Get the start date of the first leave in the current month.
     */
    private function getStartDate(Request $request): Model|LeaveAndPermission|null
    {
        return LeaveAndPermission::where('user_id', $request->user()->id)
            ->where('confirmation_status', 'Diterima')
            ->whereMonth('start_date', Carbon::now()->month)
            ->whereMonth('end_date', Carbon::now()->month)
            ->first();
    }

    /**
     * Get the latest date of the most recent leave in the current month.
     */
    private function getLatestDate(Request $request): Model|LeaveAndPermission|null
    {
        return LeaveAndPermission::where('user_id', $request->user()->id)
            ->where('confirmation_status', 'Diterima')
            ->whereMonth('start_date', Carbon::now()->month)
            ->whereMonth('end_date', Carbon::now()->month)
            ->latest()
            ->first();
    }

    /**
     * Get any pending leave applications for the current month.
     */
    private function getDiproses(Request $request): Model|LeaveAndPermission|null
    {
        return LeaveAndPermission::where('user_id', $request->user()->id)
            ->where('confirmation_status', 'Diproses')
            ->whereMonth('start_date', Carbon::now()->month)
            ->whereMonth('end_date', Carbon::now()->month)
            ->first();
    }

    /**
     * Calculate the total number of leave days taken in the current month.
     */
    private function calculateLeaveDaysInThisMonth($getStartDate, $getLatestDate): int
    {
        return Carbon::parse($getStartDate?->start_date)->diffInDays($getLatestDate?->end_date);
    }

    /**
     * Calculate the difference in days between the start date and end date of the request.
     */
    private function calculateDiffDays($startDate, $endDate): int
    {
        return Carbon::parse($startDate)->diffInDays($endDate);
    }

    /**
     * Validate the start date.
     */
    private function validateStartDate(Request $request, $getDiproses): Closure
    {
        return function ($attribute, $value, $fail) use ($request, $getDiproses) {
            $minimumDate = Carbon::now()->addDays(6);

            if ($value < $minimumDate && $request->leaves_status === 'Cuti') {
                return $fail('Pengajuan cuti minimal 7 hari sebelum tanggal mulai cuti');
            }

            if ($request->leaves_status === 'Cuti' && $this->calculateUserLeaves->calculate($request) <= 0) {
                return $fail('Jatah cuti anda telah habis');
            }

            if ($getDiproses && $request->leaves_status === 'Cuti') {
                return $fail('Cuti anda masih ada yang di proses!');
            }

            return null;
        };
    }

    /**
     * Validate the end date.
     */
    private function validateEndDate(
        Request $request,
                $getLeavesDaysInThisMonth,
                $getDiffDaysBetweenStartDateAndEndDate
    ): Closure
    {
        return static function ($attribute, $value, $fail) use (
            $request,
            $getLeavesDaysInThisMonth,
            $getDiffDaysBetweenStartDateAndEndDate
        ) {
            $diffInDays = Carbon::parse($request->start_date)->diffInDays($value);

            if ($diffInDays > 6 && $request->leaves_status === 'Cuti') {
                return $fail('Pengajuan cuti maksimal 6 hari');
            }

            if ($getDiffDaysBetweenStartDateAndEndDate + 1 > 6 && $request->leaves_status === 'Cuti') {
                return $fail('Jatah cuti bulan ini telah habis');
            }

            if ($getLeavesDaysInThisMonth + $getDiffDaysBetweenStartDateAndEndDate + 1 > 6 && $request->leaves_status === 'Cuti' && Carbon::parse(
                    $value
                )->month === Carbon::now()->month) {
                return $fail('Jatah cuti bulan ini telah habis');
            }

            return null;
        };
    }


    public function ifDateRangeHasWeekHoliday($request): Closure
    {

        return static function ($attribute, $value, $fail) use ($request) {

            $user = User::find($request->user_id);

            $getHolidayFromEmployeeSchedule = EmployeeSchedule::where('employee_id', $user->absent_id)
                ->whereDate('start_date', $request->start_date)
                ->orWhereDate('end_date', $request->end_date)
                ->first();
            $getHolidayFromWeekHoliday = WeekHoliday::where('user_id', $request->user_id)->first()?->week_holiday;

            $weekHoliday = Carbon::parse($getHolidayFromEmployeeSchedule->start_date)->dayName ?? $getHolidayFromWeekHoliday;

            $datePeriod = CarbonPeriod::create($request->start_date, $request->end_date);

            foreach ($datePeriod as $date) {
                if (Carbon::parse($date->format('Y-m-d'))->dayName === $weekHoliday) {
                    return $fail('Tanggal cuti tidak boleh ada minggu libur');
                }
            }

            return null;
        };
    }


    public function ifDateRangeHasLeaves($request): Closure
    {
        return static function ($attribute, $value, $fail) use ($request) {
            if ($request->route('leaveAndPermission')) {
                return null;
            }

            $user = User::find($request->user_id);

            $getLeaves = LeaveAndPermission::where('user_id', $user->id)->where('confirmation_status', '!=', 'Ditolak')
                ->where(function ($query) use ($request) {
                    $query
                        ->whereBetween('start_date', [$request->start_date, $request->end_date])
                        ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
                })->get();

            $leavePeriods = [];

            foreach ($getLeaves as $dates) {
                $period = CarbonPeriod::create($dates->start_date, $dates->end_date);
                foreach ($period as $date) {
                    $formattedDate = $date->format('Y-m-d');
                    $leavePeriods[$formattedDate] = [
                        'date' => $formattedDate,
                        'leaves_status' => $dates->leaves_status,
                    ];
                }
            }


            foreach ($leavePeriods as $date) {
                if ($date['date'] === $request->start_date || $date['date'] === $request->end_date) {
                    $word = "Anda sudah mengajukan" . " " . $dates->leaves_status . " " . "pada tanggal " . implode(', ', array_keys($leavePeriods));
                    return $fail($word);
                }
            }
            return null;
        };
    }
    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'start_date.required' => 'Tanggal awal tidak boleh kosong',
            'start_date.date' => 'Tanggal awal harus berupa tanggal',
            'start_date.after' => 'Pengajuan cuti minimal 6 hari sebelum hari ini',
            'end_date.required' => 'Tanggal akhir tidak boleh kosong',
            'end_date.date' => 'Tanggal akhir harus berupa tanggal',
        ];
    }
}
