<?php

namespace App\Http\Requests\Allowances;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

class UserHasOvertimeRequest extends FormRequest
{
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
        return [
            'user_id' => [
                'required',
                'exists:users,id',
                $this->validateUserFixedSalary($request),
            ],
            'hours' => ['required', 'numeric'],
            'reason' => ['required', 'string'],
        ];
    }

    public function validateUserFixedSalary($request): Closure
    {
        return function ($attribute, $value, $fail) use ($request) {
            $user = User::with('jobInformation')->where('id', $value)->first();
            if (empty($user->jobInformation->fixed_salary)) {
                $fail('data gaji pokok karyawan belum di input');
            }
        };
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Karyawan tidak boleh kosong',
            'date.required' => 'Tanggal tidak boleh kosong',
            'date.date' => 'Tanggal tidak valid',
            'hours.required' => 'Jam tidak boleh kosong',
            'hours.numeric' => 'Jam tidak valid',
            'reason.required' => 'alasan lembur tidak boleh kosong',
        ];
    }
}
