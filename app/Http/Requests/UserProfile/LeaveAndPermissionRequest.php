<?php

namespace App\Http\Requests\UserProfile;

use App\Models\LeaveAndPermission;
use App\Support\User\LeaveAndPermission\CalculateUserLeaves;
use Carbon\Carbon;
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
        $getDiproses = $this->getDiproses($request);
        $ifTotalCutiIsLargerThanSix = $this->ifTotalCutiIsLargerThanSix($request);


        return [
            'start_date' => [
                'required',
                'date',
                $ifTotalCutiIsLargerThanSix
            ],
            'end_date' => [
                Rule::requiredIf($request->leaves_status === 'Izin' || $request->leaves_status === 'Sakit' || $request->leaves_status === 'Cuti'),
            ],
            'reason' => [Rule::requiredIf($request->leaves_status === 'Izin' || $request->leaves_status === 'Sakit')],
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

        return LeaveAndPermission::where('user_id', $request->user_id ?? $request->user()->id)
            ->where('confirmation_status', 'Diterima')
            ->whereMonth('start_date', Carbon::now()->month)
            ->whereMonth('end_date', Carbon::now()->month)
            ->first();
    }


    public function getTotalLeavesDayInThisMonth(Request $request): float|int
    {
        $periodStart = Carbon::now()->startOfMonth();
        $periodEnd = Carbon::now()->endOfMonth();
        $totalDays = 0;


        $getLeaves = LeaveAndPermission::where('leaves_status', 'Cuti')
            ->where('user_id', $request->user_id ?? $request->user()->id)
            ->where('confirmation_status', 'Diterima')
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


    public function ifTotalCutiIsLargerThanSix(Request $request): Closure
    {
        $totalLeavesInThisMonth = $this->getTotalLeavesDayInThisMonth($request);
        $totalLeavesRemaining = $this->calculateUserLeaves->calculate($request);

        return static function ($attribute, $value, $fail) use ($request, $totalLeavesInThisMonth, $totalLeavesRemaining) {
            $leaveIn1MonthQuota = 6;
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $leavesPeriod = $startDate->diffInDays($endDate) + 1;
            $totalLeavesInThisMonthAndTotalLeavesPeriod = $leavesPeriod + $totalLeavesInThisMonth;


            if ($totalLeavesInThisMonthAndTotalLeavesPeriod > $leaveIn1MonthQuota) {
                return $fail(
                    'Jumlah cuti dalam satu bulan tidak boleh lebih dari 6 hari,
                     jumlah cuti tersisa untuk bulan ini :
                      ' . $leaveIn1MonthQuota - $totalLeavesInThisMonth . ' Hari'
                );
            };


            if ($totalLeavesRemaining === 0 && $request->leaves_status === 'Cuti') {
                return $fail('Jatah Cuti sudah habis');
            }

            return null;
        };
    }

    /**
     * Get any pending leave applications for the current month.
     */
    private function getDiproses(Request $request): Model|LeaveAndPermission|null
    {
        return LeaveAndPermission::where('user_id', $request->user_id)
            ->where('confirmation_status', 'Diproses')
            ->where('leaves_status', 'Cuti')
            ->whereMonth('start_date', Carbon::now()->month)
            ->whereMonth('end_date', Carbon::now()->month)
            ->first();
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
            'sick_letter.required' => 'Surat Sakit tidak boleh kosong.'
        ];
    }
}
