<?php

namespace App\Http\Requests\UserProfile;

use App\Enum\LeaveAndPermission\ConfirmationStatus;
use App\Enum\LeaveAndPermission\LeaveType;
use App\Models\LeaveAndPermission;
use App\Support\User\LeaveAndPermission\CalculateUserLeaves;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeaveAndPermissionRequest extends FormRequest
{
    private CalculateUserLeaves $calculateUserLeaves;
    public const string RUN_OUT_LEAVE_QUOTA_FAIL_VALIDATION_MESSAGE = 'Jatah Cuti sudah habis';
    private static int $leaveIn1MonthQuota = 6;

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
        $ifTotalLeaveIsLargerThanSixDays = $this->ifTotalLeavesIsLargerThanSixDays($request);
        return [
            'start_date' => [
                'required',
                'date',
                $ifTotalLeaveIsLargerThanSixDays
            ],
            'end_date' => [
                Rule::requiredIf(
                    $request->input('leaves_status') === LeaveType::PERMISSION->value
                    || $request->input('leaves_status') === LeaveType::SICK->value
                    || $request->input('leaves_status') === LeaveType::PAID_LEAVE->value),
                'after_or_equal:start_date'
            ],
            'reason' => [
                Rule::requiredIf(
                    $request->input('leaves_status') === LeaveType::PERMISSION->value
                    || $request->input('leaves_status') === LeaveType::SICK->value)
            ],
            'leaves_status' => ['required'],
            'sick_letter' => [
                Rule::requiredIf(fn() => $request->input('leaves_status') === LeaveType::SICK->value),
                'mimes:jpg,png,jpeg',
                'max:2048',
            ],
        ];
    }


    public function getTotalLeavesDayInThisMonth(Request $request): float|int
    {
        $periodStart = Carbon::now()->startOfMonth();
        $periodEnd = Carbon::now()->endOfMonth();
        $totalDays = 0;


        $getLeaves = LeaveAndPermission::where('leaves_status', LeaveType::PAID_LEAVE->value)
            ->where('user_id', $request->user_id ?? $request->user()->id)
            ->where('confirmation_status', ConfirmationStatus::ACCEPTED->value)
            ->whereMonth('start_date', Carbon::now()->month)
            ->get();

        foreach ($getLeaves as $leave) {
            $leaveStart = Carbon::parse($leave->start_date);
            $leaveEnd = Carbon::parse($leave->end_date);
            $overlapStart = $leaveStart->max($periodStart);
            $overlapEnd = $leaveEnd->min($periodEnd);
            if ($overlapStart->gt($overlapEnd)) continue;
            $totalDays += $overlapStart->diffInDays($overlapEnd) + 1;
        }
        return $totalDays;
    }


    public function ifTotalLeavesIsLargerThanSixDays(Request $request): Closure
    {
        $totalLeavesInThisMonth = $this->getTotalLeavesDayInThisMonth($request);
        $totalLeavesRemaining = $this->calculateUserLeaves->calculate($request);

        return static function ($attribute, $value, $fail) use ($request, $totalLeavesInThisMonth, $totalLeavesRemaining) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $leavesPeriod = $startDate->diffInDays($endDate) + 1;
            $totalLeavesInThisMonthAndTotalLeavesPeriod = $leavesPeriod + $totalLeavesInThisMonth;


            if ($totalLeavesInThisMonthAndTotalLeavesPeriod > self::$leaveIn1MonthQuota && $request->leaves_status === LeaveType::PAID_LEAVE->value) {
                return $fail(
                    'Jumlah cuti dalam satu bulan tidak boleh lebih dari 6 hari,
                     jumlah cuti tersisa untuk bulan ini :
                      ' . self::$leaveIn1MonthQuota - $totalLeavesInThisMonth . ' Hari'
                );
            };


            if ($totalLeavesRemaining === 0 && $request->leaves_status === LeaveType::PAID_LEAVE->value) {
                return $fail(self::RUN_OUT_LEAVE_QUOTA_FAIL_VALIDATION_MESSAGE);
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
            'end_date.required' => 'Tanggal akhir tidak boleh kosong',
            'leaves_status.required' => 'Status Cuti tidak boleh kosong',
            'sick_letter.required' => 'Surat Sakit tidak boleh kosong.',
            'end_date.after_or_equal' => 'Tanggal akhir harus setelah tanggal awal'
        ];
    }
}
