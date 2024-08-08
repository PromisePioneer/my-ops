<?php

namespace App\Http\Requests\UserProfile;

use App\Models\LeaveAndPermission;
use App\Service\CalculateUserLeaves;
use Carbon\Carbon;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        $getMonth = Carbon::now()->month;
        $date = Carbon::now()->addDays(6);
        $getStartDate = LeaveAndPermission::where('user_id', $request->user()->id)
            ->where('confirmation_status', 'Diterima')
            ->whereMonth('start_date', Carbon::now()->month)
            ->whereMonth('end_date', Carbon::now()->month)
            ->first();


        $getLatestDate = LeaveAndPermission::where('user_id', $request->user()->id)
            ->where('confirmation_status', 'Diterima')
            ->whereMonth('start_date', Carbon::now()->month)
            ->whereMonth('end_date', Carbon::now()->month)
            ->latest()
            ->first();


        $getDiproses = LeaveAndPermission::where('user_id', $request->user()->id)
            ->where('confirmation_status', 'Diproses')
            ->whereMonth('start_date', Carbon::now()->month)
            ->whereMonth('end_date', Carbon::now()->month)
            ->first();

        $getLeavesDaysInThisMonth = Carbon::parse($getStartDate?->start_date)->diffInDays($getLatestDate?->end_date);
        $getDiffDaysBetweenStartDateAndEndDate = Carbon::parse($request->start_date)->diffInDays($request->end_date);

        return [
            'start_date' => ['required', 'date', function ($attribute, $value, $fail) use ($request, $date, $getDiproses) {
                if ($value < $date && $request->leaves_status === 'Cuti') {
                    return $fail('Pengajuan cuti minimal 7 hari sebelum tanggal mulai cuti');
                }
                if ($request->leaves_status === 'Cuti' && $this->calculateUserLeaves->calculate($request) <= 0) {
                    return $fail('Jatah cuti anda telah habis');
                }

                if ($getDiproses && $request->leaves_status === 'Cuti') {
                    return $fail('cuti anda masih ada yang di proses!');
                }

                return null;
            }],
            'end_date' => ['required', 'date', 'after_or_equal:start_date', function ($attribute, $value, $fail) use ($request, $getLeavesDaysInThisMonth, $getDiffDaysBetweenStartDateAndEndDate) {
                $diffInDays = Carbon::parse($request->start_date)->diffInDays($value);
                if ($diffInDays > 6 && $request->leaves_status === 'Cuti') {
                    return $fail('Pengajuan cuti maksimal 6 hari');
                }

                if ($getDiffDaysBetweenStartDateAndEndDate + 1 > 6 && $request->leaves_status === 'Cuti') {
                    return $fail('Jatah cuti bulan ini telah habis');
                }

                if ($getLeavesDaysInThisMonth + $getDiffDaysBetweenStartDateAndEndDate + 1 > 6 && $request->leaves_status === 'Cuti' && Carbon::parse($value)->month === Carbon::now()->month) {
                    return $fail('Jatah cuti bulan ini telah habis');
                }
                return null;
            }],
            'reason' => ['required'],
            'leaves_status' => ['required'],
            'sick_letter' => [
                Rule::requiredIf(static function () use ($request) {
                return $request->leaves_status === 'Sakit';
                })
            ],
        ];
    }


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
